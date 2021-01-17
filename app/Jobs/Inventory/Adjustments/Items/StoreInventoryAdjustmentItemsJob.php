<?php

namespace App\Jobs\Inventory\Adjustments\Items;

use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
use App\Jobs\Items\Kit\UpdateKitByInvoiceItemsJob;
use App\Jobs\Items\Profit\UpdateItemProfitByInvoiceItem;
use App\Jobs\Items\Serial\UpdateItemSerialStatusByInvoiceItemJob;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class StoreInventoryAdjustmentItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice, $items, $loggedUser;
    /**
     * @var bool
     */
    private $isDraft;
    /**
     */
    private $createdAt;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param $items
     * @param bool $isDraft
     * @param null $loggedUser
     * @param bool $isOnlineOrder
     */
    public function __construct(Invoice $invoice, $items, $isDraft = false, $createdAt = null)
    {
        $this->items = $items;
        $this->invoice = $invoice;
        $this->loggedUser =  auth()->user();
        $this->isDraft = $isDraft;
        $this->createdAt = $createdAt ? $createdAt : Carbon::now();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ((array)$this->items as $key => $value) {
            $item = Item::find($value['id']);
            $this->storeItem($item, $value);
        }
    }



    private function storeItem(Item $item, $requestItemData)
    {

        $requestItemCollection = collect($requestItemData);

        /**
         * ==========================================================
         * store available qty/cost before create new item
         * ==========================================================
         */
        $invoiceItemBefore = $item->piplineWithoutSorting()->where('created_at', '<', $this->createdAt)->withoutGlobalScopes(["manager"])->orderBy('created_at', 'desc')->first();
        $availableQtyBeforeInvoiceItem = $invoiceItemBefore ? $invoiceItemBefore->available_qty : 0;
        $costBeforeInvoiceItem = $item->cost;
        $stockQty  = $item->is_need_serial ? count($requestItemCollection->get('serials')) : $requestItemCollection->get('qty');
        /**
         * ==========================================================
         * create new invoice item instance
         * ==========================================================
         */
        $invoiceItem = $this->createInvoiceItem($item, $requestItemCollection, $costBeforeInvoiceItem, $availableQtyBeforeInvoiceItem,$stockQty);


        /**
         * ==========================================================
         * change actual item data if it's not draft items
         * ==========================================================
         */
        if (!$this->isDraft) {


            /**
             * ==========================================================
             * if it need serial change the serial list status
             * ==========================================================
             */
            if ($item->is_need_serial) {
                dispatch_now(new UpdateItemSerialStatusByInvoiceItemJob($requestItemCollection->get('serials'), $invoiceItem, $this->isDraft));
            }


            if (!$item->is_service) {
                /**
                 * ==========================================================
                 * update qty should be before update cost
                 * ==========================================================
                 */
                $item->update([
                    'available_qty' => $stockQty
                ]);
                // dispatch_now(new UpdateAvailableQtyByInvoiceItemJob($invoiceItem));
                /**
                 * ==========================================================
                 * we neeed for available qty and cost before new invoice item
                 * ==========================================================
                 */
                dispatch_now(new UpdateItemCostByInvoiceItemJob($invoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));

                /**
                 * ==========================================================
                 * set cost and available qty to the created invoice item
                 * ==========================================================
                 */
                $this->setCostAndAvailableQty($invoiceItem);


                $this->normalizeUpcomingPipeline($item,$invoiceItem);
            }

        }
    }


    public function normalizeUpcomingPipeline($item,$invoiceItem)
    {
        $invoiceItemsAfter = $item->piplineWithoutSorting()->where([
            ['created_at', '>', $this->createdAt],
            ['id','!=',$invoiceItem->id]
        ])->orderBy('created_at', 'asc')->get();

        foreach ($invoiceItemsAfter as $historyInvoiceItem) {
            $availableQtyBeforeInvoiceItem = $historyInvoiceItem->item->available_qty;
            $costBeforeInvoiceItem = $historyInvoiceItem->item->cost;

            dispatch_now(new UpdateAvailableQtyByInvoiceItemJob($historyInvoiceItem));
            /**
             * ==========================================================
             * we neeed for available qty and cost before new invoice item
             * ==========================================================
             */
            dispatch_now(new UpdateItemCostByInvoiceItemJob($historyInvoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));

            
            $this->setCostAndAvailableQty($historyInvoiceItem);
        }
    }


    private function performDBCreation($data)
    {
        return $this->invoice->items()->create($data);
    }

    private function createInvoiceItem(Item $item, $requestItemCollection, $costBeforeInvoiceItem, $availableQtyBeforeInvoiceItem,$stockQty)
    {

       
        $isBelongToKit = false;
        $parentKitId = 0;
        $price = (float)$costBeforeInvoiceItem;
        $discount = (float)0;
        $qty = (int)$stockQty - $availableQtyBeforeInvoiceItem; // 10
        $total = $price * $qty;
        $subtotal = $total - $discount;
        $tax = 0;
        $net = $subtotal + $tax;
        $data['belong_to_kit'] = $isBelongToKit;
        $data['parent_kit_id'] = $parentKitId;
        $data['invoice_type'] = 'inventory_adjustment';
        $data['user_id'] = $this->invoice->user_id;
        $data['qty'] = $qty;
        $data['price'] = $price;
        $data['discount'] = $discount;
        $data['total'] = $total;
        $data['subtotal'] = $subtotal;
        $data['tax'] = $tax;
        $data['net'] = $net;
        $data['organization_id'] = $this->loggedUser->organization_id;
        $data['creator_id'] = $this->loggedUser->id;
        $data['item_id'] = $item->id;
        $data['is_draft'] = $this->isDraft;
        $data['created_at'] =  $this->createdAt;
        $data['updated_at'] =  $this->createdAt;
        return $this->performDBCreation($data);
    }

    private function setCostAndAvailableQty(InvoiceItems $invoiceItem)
    {
        $invoiceItem->update(
            [
                'cost' => $invoiceItem->item->fresh()->cost,
                'available_qty' => $invoiceItem->item->fresh()->available_qty,
                'total_stock_cost_amount' => (float)$invoiceItem->item->fresh()->cost * (float)$invoiceItem->item->fresh()->available_qty,
            ]
        );
    }
}
