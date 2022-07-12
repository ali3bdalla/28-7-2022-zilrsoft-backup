<?php

namespace App\Jobs\Sales\Items;

use App\Enums\InvoiceItemStatusEnum;
use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
use App\Jobs\Items\Kit\UpdateKitByInvoiceItemsJob;
use App\Jobs\Items\Profit\UpdateItemProfitByInvoiceItem;
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

class StoreReturnSaleItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $returnedItems;


    /**
     * @var Invoice|null
     */
    private $saleInvoice;
    /**
     * @var Invoice|null
     */
    private $returnSaleInvoice;
    /**
     * @var Authenticatable|null
     */
    private $loggedUser;
    /**
     * @var bool
     */
    private $isDraft;
    private ?InvoiceItemStatusEnum $invoiceItemStatusEnum;

    /**
     * Create a new job instance.
     *
     * @param Invoice $returnSaleInvoice
     * @param Invoice $saleInvoice
     * @param $returnedItems
     * @param bool $isDraft
     * @param InvoiceItemStatusEnum|null $invoiceItemStatusEnum
     */
    public function __construct(Invoice $returnSaleInvoice, Invoice $saleInvoice, $returnedItems, $isDraft = false,InvoiceItemStatusEnum $invoiceItemStatusEnum = null)
    {

        $this->returnSaleInvoice = $returnSaleInvoice;
        $this->saleInvoice = $saleInvoice;
        $this->returnedItems = $returnedItems;
        $this->loggedUser = auth()->user();
        $this->isDraft = $isDraft;
        $this->invoiceItemStatusEnum = $invoiceItemStatusEnum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        foreach ((array)$this->returnedItems as $key => $value) {
            $invoiceItem = InvoiceItems::find($value['id']);
            if ($invoiceItem->item->is_kit) {
                $this->storeKit($invoiceItem, $value);
            } else {
                $this->storeItem($invoiceItem, $value);
            }
        }
    }


    public function storeKit(InvoiceItems $invoiceItem, $value)
    {
        $kitRequestCollection = collect($value);

        $qty = (float)$kitRequestCollection->get('returned_qty');
        $qtyWidget = $qty / $invoiceItem->qty;



        $data['belong_to_kit'] = false;
        $data['parent_kit_id'] = 0;
        $data['discount'] = $invoiceItem->discount * $qtyWidget; //(float)($dbItem->data->discount * $qty)
        $data['price'] = $invoiceItem->price; //(float)$dbItem->data->total
        $data['qty'] = $qty;
        $data['total'] = $invoiceItem->total * $qtyWidget;
        $data['subtotal'] = $invoiceItem->subtotal * $qtyWidget;
        $data['net'] = $invoiceItem->net * $qtyWidget;
        $data['organization_id'] = $this->returnSaleInvoice->organization_id;
        $data['creator_id'] = $this->returnSaleInvoice->creator_id;
        $data['item_id'] = $invoiceItem->item_id;
        $data['user_id'] = $this->returnSaleInvoice->user_id;
        $data['invoice_type'] = $this->returnSaleInvoice->invoice_type;
        $data['is_kit'] = true;
        $data['status'] = $this->invoiceItemStatusEnum;
        $createdInvoiceKitItem = $this->returnSaleInvoice->items()->create($data);
        $this->storeKitItems($kitRequestCollection, $createdInvoiceKitItem, $invoiceItem, $qty);
        dispatch_sync(new UpdateKitByInvoiceItemsJob($createdInvoiceKitItem));
        $this->updateInvoiceItemReturnQty($invoiceItem, $createdInvoiceKitItem);
    }

    public function storeKitItems($kitRequestCollection, InvoiceItems $returnSalesKitInvoiceItem, InvoiceItems $SalesKitInvoiceItem, $kitReturnedQty)
    {
        $KitItems = $this->saleInvoice->items()->where([
            ['belong_to_kit', true],
            ['parent_kit_id', $SalesKitInvoiceItem->id],
        ])->get();


        foreach ($KitItems as $kitItem) {
            $dbItem = $kitItem->item;

            $kitItemReturnedQty = ($kitItem->qty / $SalesKitInvoiceItem->qty) * $kitReturnedQty;
            if ($dbItem->is_need_serial) {
                $kitItemRequestData = collect(collect($kitRequestCollection->get('items'))->where('id', $kitItem->item_id)->first());
                if (!empty($kitItemRequestData)) {
                    $requestData['serials'] = $kitItemRequestData->get('serials');
                } else {
                    $requestData['serials'] = [];
                }
            }

            $requestData['returned_qty'] = $kitItemReturnedQty;
            $requestData['belong_to_kit'] = true;
            $requestData['parent_kit_id'] = $returnSalesKitInvoiceItem->id;
            $requestData['id'] = $dbItem->id;
            /***
             * =====================================
             * create new items
             * =====================================
             */
            $this->storeItem($kitItem, $requestData);
        }
    }


    private function storeItem(InvoiceItems $invoiceItem, $value)
    {

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
        $createdInvoiceItem = $this->createInvoiceItem($invoiceItem, $requestItemCollection);


        /**
         * ==========================================================
         * if it need serial change the serial list status
         * ==========================================================
         */
        if ($createdInvoiceItem->item->is_need_serial) {
            dispatch_sync(new UpdateItemSerialStatusByInvoiceItemJob($requestItemCollection->get('serials'), $createdInvoiceItem, $this->isDraft));
        }


        /**
         * ==========================================================
         * change actual item data if it's not draft items
         * ==========================================================
         */
        if (!$this->isDraft) {
            /**
             * ==========================================================
             * update qty should be before update cost
             * ==========================================================
             */
            dispatch_sync(new UpdateAvailableQtyByInvoiceItemJob($createdInvoiceItem));
            /**
             * ==========================================================
             * we need for available qty and cost before new invoice item
             * ==========================================================
             */
            dispatch_sync(new UpdateItemCostByInvoiceItemJob($createdInvoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));

            /**
             * ==========================================================
             * set cost and available qty to the created invoice item
             * ==========================================================
             */
            $this->setCostAndAvailableQty($createdInvoiceItem);

            /**
             * ==========================================================
             * update items total profits amount
             * ==========================================================
             */
            dispatch_sync(new UpdateItemProfitByInvoiceItem($createdInvoiceItem));


            $this->updateInvoiceItemReturnQty($invoiceItem, $createdInvoiceItem);
        }
    }


    public function createInvoiceItem(InvoiceItems $invoiceItem, $value): InvoiceItems
    {
        $requestItemCollection = collect($value);
        $isBelongToKit = (bool)$requestItemCollection->get('belong_to_kit');
        $salesPrice = $invoiceItem->price;
        $returnedQty = (float)$requestItemCollection->get('returned_qty');
        if ($returnedQty > 0)
            $returnedQtyWidget = $returnedQty / $invoiceItem->qty; //0.1
        else
            $returnedQtyWidget = 0;

        $total = (float)$salesPrice * $returnedQty; // 0.1
        $discount = (float) $invoiceItem->discount * (float)$returnedQtyWidget; //0
        $subtotal = (float) $total - (float)$discount;
        $tax = (float)$invoiceItem->tax * $returnedQtyWidget;
        $net = (float)$subtotal + (float)$tax;

        $data['parent_kit_id'] = $isBelongToKit ? (float)$requestItemCollection->get('kit_id') : 0;
        $data['belong_to_kit'] = $isBelongToKit;
        $data['price'] = $salesPrice;
        $data['invoice_type'] = $this->returnSaleInvoice->invoice_type;
        $data['user_id'] = $this->returnSaleInvoice->user_id;
        $data['qty'] = $returnedQty;
        $data['discount'] = $discount;
        $data['total'] = $total;
        $data['subtotal'] = $subtotal;
        $data['tax'] = $tax;
        $data['net'] = $net;
        $data['organization_id'] = $this->loggedUser->organization_id;
        $data['creator_id'] = $this->loggedUser->id;
        $data['item_id'] = $invoiceItem->item_id;
        $data['is_draft'] = $this->isDraft;
        $data['status'] = $this->invoiceItemStatusEnum;
        return $this->returnSaleInvoice->items()->create($data);
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
