<?php

namespace App\Jobs\Accounting\Purchase;

use App\Jobs\Items\Accounting\UpdateItemAccountingBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StorePurchaseTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice, $vendorAccount, $loggedUser, $startupData, $stockAccount, $taxAccount;

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
        $transactionContainer = new TransactionsContainer(
            [
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'invoice_id' => $invoice->id,
                'amount' => 0,
                'description' => 'Generated From Invoice #' . $invoice->invoice_number 
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
        $this->createVendorBalanceTransaction();
        $this->createTaxTransaction();

    }

    private function createItemsTransactions()
    {

        $items = $this->invoice->items;
        foreach ($items as $item) {
            $data = $this->startupData;
            $data['amount'] = $item->subtotal;
            $data['user_id'] = $this->invoice->purchase->vendor_id;
            $data['type'] = 'debit';
            $data['item_id'] = $item['item_id'];
            $this->stockAccount->transactions()->create($data);
            dispatch(new UpdateItemAccountingBalanceJob($item->item,$item->subtotal,'debit'));
        }

    }

    private function createVendorBalanceTransaction()
    {
        $data = $this->startupData;
        $data['amount'] = $this->invoice->net;
        $data['user_id'] = $this->invoice->purchase->vendor_id;
        $data['type'] = 'credit';
        $this->vendorAccount->transactions()->create($data);
        dispatch(new UpdateVendorBalanceJob($this->invoice->purchase->vendor, $this->invoice->net, 'increase'));
    }

    private function createTaxTransaction()
    {
        $data = $this->startupData;
        $data['amount'] = $this->invoice->tax;
        $data['type'] = 'debit';
        $this->taxAccount->transactions()->create($data);
    }
}
