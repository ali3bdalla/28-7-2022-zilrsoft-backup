<?php

namespace App\Jobs\Accounting\Purchase;

use App\Jobs\Items\Accounting\UpdateItemAccountingBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreReturnPurchaseTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice, $vendorAccount, $loggedUser, $startupData, $stockAccount, $taxAccount, $amountPaidViaMethods = 0;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->vendorAccount = Account::where('slug', 'vendors')->first();
        $this->stockAccount = Account::where('slug', 'stock')->first();
        $this->taxAccount = Account::where('slug', 'vat')->first();
        $this->loggedUser = auth()->user();
        $transactionContainer = new Entry(
            [
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'invoice_id' => $invoice->id,
                'amount' => 0,
	            'description' => 'Invoice #' . $invoice->id
            ]
        );
        $transactionContainer->save();
        $this->startupData = [
            'organization_id' => $this->invoice->organization_id,
            'creator_id' => $this->loggedUser->id,
            'container_id' => $transactionContainer->id,
            'invoice_id' => $this->invoice->id,
        ];
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->createItemsTransactions();
        $this->createPaymentsTransactions();
        $this->createVendorBalanceTransaction();
        $this->createTaxTransaction();

    }

    private function createItemsTransactions()
    {

        $items = $this->invoice->items;
        foreach ($items as $item) {
            $data = $this->startupData;
            $data['amount'] = $item->subtotal;
            $data['user_id'] = $this->invoice->user_id;
            $data['type'] = 'credit';
            $data['item_id'] = $item['item_id'];
            $this->stockAccount->transactions()->create($data);
            dispatch_sync(new UpdateItemAccountingBalanceJob($item->item, $item->subtotal, 'credit'));
        }

    }

    private function createPaymentsTransactions()
    {
        $data = $this->startupData;
        $data['type'] = 'debit';
        $data['user_id'] = $this->invoice->user_id;
        foreach ($this->invoice->payments()->get() as $key => $payment) {
            $data['amount'] = $payment->amount;
            $payment->account->transactions()->create($data);
            $this->amountPaidViaMethods += $data['amount'];
        }
    }

    private function createVendorBalanceTransaction()
    {

        $unpaidAmount = (float)$this->invoice->net - (float)$this->amountPaidViaMethods;
        $data = $this->startupData;

        if ($unpaidAmount != 0) {
            if ($unpaidAmount < 0) {
                $data['type'] = 'credit';
                $balanceUpdate = 'increase';
            } else {
                $data['type'] = 'debit';
                $balanceUpdate = 'decrease';
            }

            $data['amount'] = abs($unpaidAmount);
            $data['user_id'] = $this->invoice->user_id;
            $this->vendorAccount->transactions()->create($data);
            dispatch_sync(new UpdateVendorBalanceJob($this->invoice->user, abs($unpaidAmount), $balanceUpdate));

        }
    }

    private function createTaxTransaction()
    {
        $data = $this->startupData;
        $data['amount'] = $this->invoice->tax;
        $data['type'] = 'credit';
        $this->taxAccount->transactions()->create($data);
    }
}
