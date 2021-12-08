<?php

namespace App\Jobs\Inventory\Adjustments\Items;

use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
use App\Jobs\Items\Serial\UpdateItemSerialStatusByInvoiceItemJob;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Item;
use App\Models\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StoreInventoryAdjustmentItemsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Invoice $invoice;
    private array $items;
    private Manager $loggedUser;

    private bool $isDraft;


    public function __construct(Invoice $invoice, array $items, bool $isDraft = false)
    {
        $this->items = $items;
        $this->invoice = $invoice;
        $this->loggedUser = Auth::user();
        $this->isDraft = $isDraft;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->items as $value) {
            $item = Item::find($value['id']);
            $this->storeItem($item, $value);
        }
    }

    private function storeItem(Item $item, $requestItemData)
    {
        $requestItemCollection = collect($requestItemData);
        $lastAvailableQtyBeforeNewTransaction = $item->available_qty;
        $costBeforeInvoiceItem = $item->cost;
        $expectedQty = $item->is_need_serial ? count($requestItemCollection->get('serials')) : $requestItemCollection->get('qty'); // 8
        $newTransactionQty = $expectedQty - $item->available_qty;
        $newAvailableQty = $item->available_qty + $newTransactionQty;
        $invoiceItem = $this->createInvoiceItem($item, $costBeforeInvoiceItem, $newTransactionQty);
        if (!$this->isDraft) {
            if ($item->is_need_serial) {
                dispatch_sync(new UpdateItemSerialStatusByInvoiceItemJob($requestItemCollection->get('serials'), $invoiceItem, $this->isDraft));
            }
            if (!$item->is_service) {
                $item->update([
                    'available_qty' => $newAvailableQty,
                ]);
                dispatch_sync(new UpdateItemCostByInvoiceItemJob($invoiceItem, $lastAvailableQtyBeforeNewTransaction, $costBeforeInvoiceItem));
                $this->setCostAndAvailableQty($invoiceItem);
            }
        }
    }

    private function createInvoiceItem(Item $item, $costBeforeInvoiceItem, $newTransactionQty): Model
    {
        $isBelongToKit = false;
        $parentKitId = 0;
        $price = (float)$costBeforeInvoiceItem;
        $discount = (float)0;
        $qty = (float)$newTransactionQty;
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

        return $this->performDBCreation($data);
    }

    private function performDBCreation($data): Model
    {
        return $this->invoice->items()->create($data);
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
