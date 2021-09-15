<?php

namespace App\Jobs\Purchases\Items;

use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
use App\Jobs\Items\Price\UpdateItemLastPurchasePriceJob;
use App\Jobs\Items\Serial\AddItemSerialByInvoiceItemJob;
use App\Jobs\Items\Serial\UpdateItemSerialStatusByInvoiceItemJob;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreReturnPurchaseItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $returnPurchaseInvoice;
    /**
     * @var Invoice
     */
    private $purchaseInvoice;
    private $items;
    /**
     * @var Authenticatable|null
     */
    private $loggedUser;

    //44
    private $isDraft;

    /**
     * Create a new job instance.
     *
     * @param Invoice $returnPurchaseInvoice
     * @param Invoice $purchaseInvoice
     * @param $items
     * @param bool $isDraft
     */
    public function __construct(Invoice $returnPurchaseInvoice, Invoice $purchaseInvoice, $items, $isDraft = false)
    {
        //
        $this->returnPurchaseInvoice = $returnPurchaseInvoice;
        $this->purchaseInvoice = $purchaseInvoice;
        $this->items = $items;
        $this->loggedUser = auth()->user();
        $this->isDraft = $isDraft;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ((array)$this->items as $key => $value) {
            $invoiceItem = InvoiceItems::find($value['id']);
            $requestItemCollection = collect($value);
            /**
             * ==========================================================
             * store available qty/cost before create new item
             * ==========================================================
             */
            $availableQtyBeforeInvoiceItem = $invoiceItem->item->available_qty;
            $costBeforeInvoiceItem = $invoiceItem->item->cost;

            /**
             * ==========================================================
             * create new invoice item instance
             * ==========================================================
             */
            $returnInvoiceItem = $this->createInvoiceItem($invoiceItem, $requestItemCollection);


            /**
             * ==========================================================
             * if it need serial change the serial list status
             * ==========================================================
             */
            if ($returnInvoiceItem->item->is_need_serial) {
                dispatch_sync(new UpdateItemSerialStatusByInvoiceItemJob($requestItemCollection->get('serials'), $returnInvoiceItem));
            }
            /**
             * ==========================================================
             * change actual item data if it's not draft items
             * ==========================================================
             */
            /**
             * ==========================================================
             * update qty should be before update cost
             * ==========================================================
             */
            dispatch_sync(new UpdateAvailableQtyByInvoiceItemJob($returnInvoiceItem));
            /**
             * ==========================================================
             * we neeed for available qty and cost before new invoice item
             * ==========================================================
             */
            dispatch_sync(new UpdateItemCostByInvoiceItemJob($returnInvoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));

            /**
             * ==========================================================
             * set cost and available qty to the created invoice item
             * ==========================================================
             */
            $this->setCostAndAvailableQty($returnInvoiceItem);


            /**
             * ==========================================================
             * update the purchase invoice return qty = returned qty before + current qty
             * ==========================================================
             */
            $this->updateInvoiceItemReturnQty($invoiceItem, $returnInvoiceItem);
        }
    }

    private function createInvoiceItem(InvoiceItems $invoiceItem, $requestItemCollection)
    {

        $purchasePrice = $invoiceItem->price;
        $returnedQty = (float)$requestItemCollection->get('returned_qty');
        $total = $purchasePrice * $returnedQty;
        $subtotal = $total;
        $tax = ($invoiceItem->tax / $invoiceItem->qty) * $returnedQty;
        $net = $subtotal + $tax;

        $data['price'] = $purchasePrice;
        $data['invoice_type'] = 'return_purchase';
        $data['user_id'] = $this->returnPurchaseInvoice->user_id;
        $data['qty'] = $returnedQty;
        $data['discount'] = 0;
        $data['total'] = $total;
        $data['subtotal'] = $subtotal;
        $data['tax'] = $tax;
        $data['net'] = $net;
        $data['organization_id'] = $this->loggedUser->organization_id;
        $data['creator_id'] = $this->loggedUser->id;
        $data['item_id'] = $invoiceItem->item_id;
        $data['is_draft'] = $this->isDraft;
        return $this->returnPurchaseInvoice->items()->create($data);
    }

    private function setCostAndAvailableQty(InvoiceItems $invoiceItem)
    {
        $invoiceItem->update([
            'cost' => $invoiceItem->item->fresh()->cost,
            'available_qty' => $invoiceItem->item->fresh()->available_qty,
            'total_stock_cost_amount' => (float)$invoiceItem->item->fresh()->cost * (float)$invoiceItem->item->fresh()->available_qty,
        ]);
    }

    private function updateInvoiceItemReturnQty(InvoiceItems $invoiceItem, InvoiceItems $returnInvoiceItem)
    {
        $invoiceItem->update([
            'returned_qty' => $invoiceItem->returned_qty + $returnInvoiceItem->qty
        ]);
    }
}
