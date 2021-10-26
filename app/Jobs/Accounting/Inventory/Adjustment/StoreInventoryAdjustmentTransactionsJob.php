<?php

namespace App\Jobs\Accounting\Inventory\Adjustment;

use App\Jobs\Items\Accounting\UpdateItemAccountingBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class StoreInventoryAdjustmentTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice, $inventoryAdjustmentAccount, $loggedUser, $startupData, $stockAccount;

    private $createdAt;
    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice, $createdAt = null)
    {
        $this->invoice = $invoice;
        $this->inventoryAdjustmentAccount = Account::where('slug', 'inventory_adjustment')->first();
        $this->stockAccount = Account::where('slug', 'stock')->first();
        $this->createdAt = $createdAt ?? Carbon::now();
        $this->loggedUser = auth()->user();
        $transactionContainer = new Entry(
            [
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'invoice_id' => $invoice->id,
                'amount' => 0,
                'description' => 'Invoice #' . $invoice->id,
                'created_at' => $this->createdAt,
                'updated_at' => $this->createdAt,
            ]
        );
        $transactionContainer->save();
        $this->startupData = [
            'organization_id' => $this->invoice->organization_id,
            'creator_id' => $this->loggedUser->id,
            'container_id' => $transactionContainer->id,
            'invoice_id' => $this->invoice->id,
            'created_at' => $this->createdAt,
            'updated_at' => $this->createdAt,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $totalDebit = 0;
        $totalCredit = 0;
        $items = $this->invoice->items;
        foreach ($items as $item) {

            $amount = abs($item->subtotal);
            $data = $this->startupData;
            $data['amount'] = $amount;
            if ($item->qty > 0) {
                $transactionType = 'debit';
                $totalDebit += $amount;
            } else {
                $transactionType = 'credit';
                $totalCredit += $amount;
            }

            $data['type'] = $transactionType;
            $data['item_id'] = $item['item_id'];
            $this->stockAccount->transactions()->create($data);
        }


        $adjustmentAmount = $totalCredit - $totalDebit;
        $data = $this->startupData;
        if ($adjustmentAmount > 0) {
            $data['type'] = 'debit';
        } else {
            $data['type'] = 'credit';
        }
        $data['amount'] = abs($adjustmentAmount);
        $this->inventoryAdjustmentAccount->transactions()->create($data);
    }
}
