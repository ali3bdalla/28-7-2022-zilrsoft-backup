<?php

namespace App\Jobs\Items\Cost;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemCostByInvoiceItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InvoiceItems $invoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem)
    {
        $this->invoiceItem = $invoiceItem;
        $this->availableQtyBeforeInvoiceItem = $availableQtyBeforeInvoiceItem;
        $this->costBeforeInvoiceItem = $costBeforeInvoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /**
         * ==========================================================
         * update cost for purchase invoice item
         * stock amount = old stock amount + invoice item subtotal
         * ==========================================================
         */
        if (in_array($this->invoiceItem->invoice_type, ['purchase'])) {
            $stockAmountBeforeNewInvoiceItem = $this->availableQtyBeforeInvoiceItem * $this->costBeforeInvoiceItem;
            $newStockAmount = (float) $stockAmountBeforeNewInvoiceItem + (float) $this->invoiceItem->total;
            $newItemCost = (float) $newStockAmount / (int) $this->invoiceItem->item->fresh()->available_qty;
            $this->invoiceItem->item->update([
                'cost' => $newItemCost,
            ]);
        }
    }
}
