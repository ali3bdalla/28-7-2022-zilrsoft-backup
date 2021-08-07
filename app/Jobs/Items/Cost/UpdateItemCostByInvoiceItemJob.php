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
     * @param InvoiceItems $invoiceItem
     * @param $availableQtyBeforeInvoiceItem
     * @param $costBeforeInvoiceItem
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

        $availableQty = (float)$this->invoiceItem->item->fresh()->available_qty;
        /**
         * ==========================================================
         * update cost for purchase invoice item
         * stock amount = old stock amount + invoice item subtotal
         * ==========================================================
         */
        if (in_array($this->invoiceItem->invoice_type, ['purchase', 'beginning_inventory', 'inventory_adjustment'])) {
            $stockAmountBeforeNewInvoiceItem = $this->availableQtyBeforeInvoiceItem * $this->costBeforeInvoiceItem;
            $newStockAmount = (float)$stockAmountBeforeNewInvoiceItem + (float)$this->invoiceItem->subtotal;
            $newItemCost = $availableQty == 0 ? $this->invoiceItem->item->fresh()->cost :  (float)$newStockAmount / $availableQty;
            $this->invoiceItem->item->update([
                'cost' => $newItemCost,
                'total_cost_amount' => (float)($newItemCost * $availableQty),
            ]);
        }


        /**
         * ==========================================================
         * update cost for return purchase invoice item
         * stock amount = old stock amount - invoice item subtotal
         * ==========================================================
         */
        if (in_array($this->invoiceItem->invoice_type, ['return_purchase'])) {
            $stockAmountBeforeNewInvoiceItem = $this->availableQtyBeforeInvoiceItem * $this->costBeforeInvoiceItem;
            $newStockAmount = (float)$stockAmountBeforeNewInvoiceItem - (float)$this->invoiceItem->subtotal;
            if ($availableQty == 0) {
                $newItemCost = $this->costBeforeInvoiceItem;
            } else {
                $newItemCost = (float)$newStockAmount / $availableQty;
            }
            $this->invoiceItem->item->update([
                'cost' => $newItemCost,
                'total_cost_amount' => (float)($newItemCost * $availableQty),
            ]);
        }


        /**
         * ==========================================================
         * update cost for sale invoice item
         * stock amount = available qty - cost
         * ==========================================================
         */
        if (in_array($this->invoiceItem->invoice_type, ['sale', 'return_sale'])) {
            $this->invoiceItem->item->update([
                'total_cost_amount' => (float)($this->invoiceItem->item->cost * $availableQty),
            ]);
        }
    }
}
