<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use App\InvoiceItems;
use App\Item;
use App\ItemSerials;
use App\TransactionsContainer;
use Dotenv\Exception\ValidationException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Accounting\Jobs\CreateSalesItemEntity;
use Modules\Items\Jobs\ChangeItemSerialsStatusJob;
use Modules\Items\Jobs\EnsureItemQtyIsStillMoreThanZeroJob;
use Modules\Items\Jobs\UpdateAvailableQtyForEachInvoiceItemJob;
use Modules\Items\Jobs\UpdateInvoiceItemProfitJob;
use Modules\Items\Jobs\UpdateItemCostJob;
use Modules\Items\Jobs\UpdateItemQtyJob;
use Modules\Items\Jobs\UpdateKitDataDependingOnItemsJob;
use Modules\Items\Jobs\ValidateItemSerialsJob;

class CreateSalesItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $requestItemsData;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var TransactionsContainer
     */
    private $entity;


    private $availableSerialsStatus = ["r_sale", "purchase", 'available'];

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $requestItemsData
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice, $requestItemsData = [])
    {
        //
        $this->requestItemsData = (array)$requestItemsData;
        $this->invoice = $invoice;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->requestItemsData as $index => $itemRequestData) {
            $item = Item::findOrFail($itemRequestData['id']);
            if ($item->isKit())
                $this->createKit($this->invoice, $item, $itemRequestData, $index);
            else
                $this->createItem($item, $itemRequestData, $index);
            dispatch(new EnsureItemQtyIsStillMoreThanZeroJob($item));
        }
    }

    public function createKit(Invoice $invoice, Item $dbItem, $itemRequestData, $index)
    {
        $itemPureCollection = collect($itemRequestData);
        $qty = (int)$itemPureCollection->get('qty');
        $showInPrint = (bool)$itemPureCollection->get('printable');
        if (!$showInPrint)
            $invoice->update(['printable_price' => false]);

        $data['belong_to_kit'] = false;
        $data['printable'] = $showInPrint;
        $data['parent_kit_id'] = 0;
        $data['discount'] = (float)($dbItem->data->discount * $qty);
        $data['price'] = (float)$dbItem->data->total;
        $data['qty'] = $qty;
        $data['total'] = $dbItem->moneyFormatter((float)$data['price'] * (float)$data['qty']);
        $data['subtotal'] = $dbItem->moneyFormatter((float)$data['total'] - (float)$data['discount']);
        $data['tax'] = $dbItem->moneyFormatter($dbItem->getSaleTax($data['subtotal']));
        $data['net'] = $dbItem->moneyFormatter((float)$data['subtotal'] + (float)$data['tax']);
        $data['organization_id'] = $invoice->organization_id;
        $data['creator_id'] = $invoice->creator_id;
        $data['item_id'] = $dbItem->id;
        $data['user_id'] = $invoice->user_id;
        $data['invoice_type'] = $invoice->invoice_type;
        $data['is_kit'] = true;
        $invoiceKitItem = $invoice->items()->create($data);
        $this->createKitItems($itemPureCollection, $dbItem, $invoiceKitItem, $qty, $index);
        dispatch(new UpdateKitDataDependingOnItemsJob($invoiceKitItem));

    }

    public function createKitItems($itemPureCollection, Item $dbKit, InvoiceItems $baseItem, $qty, $index)
    {

        foreach ($dbKit->items as $kitItem) {
            $item = $kitItem->item;
            if ($item->isNeedSerial()) {
                $data = collect($itemPureCollection->get('items'))->where('id', $item->id)->first();
                if (!empty($data)) {
                    $sendData['serials'] = $data['serials'];
                } else {
                    $sendData['serials'] = [];
                }
            }
            $sendData['qty'] = (int)$kitItem->qty * (int)$qty;
            $sendData['discount'] = $dbKit->moneyFormatter($kitItem['discount'] * $qty);
            $sendData['price'] = $dbKit->moneyFormatter($kitItem['price']);
            $sendData['belong_to_kit'] = true;
            $sendData['kit_id'] = $baseItem->id;
            $sendData['id'] = $item->id;

            $this->createItem($item, $sendData, $index);
        }

    }

    public function createItem($item, $itemRequestData, $index)
    {
        $invoiceItem = $this->addPureItem($this->invoice, $item, $itemRequestData, $index);
        if (!$item->isService()  && !$item->isKit() && !$this->invoice->isPending()) {
            dispatch(new UpdateItemCostJob($this->invoice, $invoiceItem));
//            exit();
            dispatch(new UpdateItemQtyJob($this->invoice, $invoiceItem));
            dispatch(new CreateSalesItemEntity($this->entity, $this->invoice, $invoiceItem));
            if ($item->isNeedSerial())
                dispatch(new ChangeItemSerialsStatusJob($item,$this->invoice, $itemRequestData['serials'], $this->availableSerialsStatus, 'saled'));
            dispatch(new UpdateInvoiceItemProfitJob($invoiceItem));
        }
        dispatch(new UpdateAvailableQtyForEachInvoiceItemJob($invoiceItem));

    }


    public function addPureItem(Invoice $invoice, Item $dbItem, $itemRequestData, $index = 0)
    {
        $itemPureCollection = collect($itemRequestData);
        $this->validateItemRequestData($dbItem, $itemPureCollection, $index); // validate Item
        $showInPrint = (bool)$itemPureCollection->get('printable');
        if (!$showInPrint)
            $invoice->update(['printable_price' => false]);

        $belongToKit = (bool)$itemPureCollection->get('belong_to_kit');
        $data['printable'] = $showInPrint;
        $data['parent_kit_id'] = $belongToKit ? (int)$itemPureCollection->get('kit_id') : 0;
        $data['belong_to_kit'] = $belongToKit;
        $data['discount'] = $dbItem->moneyFormatter($itemPureCollection->get('discount'));
        $data['price'] = $dbItem->moneyFormatter($dbItem->getSalePrice((float)$itemPureCollection->get('price')));
        $data['qty'] = (int)$itemPureCollection->get('qty');
        $data['total'] = $dbItem->moneyFormatter((float)$data['price'] * (int)$data['qty']);
        $data['subtotal'] = $dbItem->moneyFormatter((float)$data['total'] - (float)$data['discount']);
        $data['tax'] = $dbItem->moneyFormatter($dbItem->getSaleTax($data['subtotal']));
        $data['net'] = $dbItem->moneyFormatter((float)((float)$data['tax'] + (float)$data['subtotal']));
        $data['organization_id'] = $invoice->organization_id;
        $data['creator_id'] = $invoice->creator_id;
        $data['item_id'] = $dbItem->id;
        $data['user_id'] = $invoice->user_id;
        $data['invoice_type'] = $invoice->invoice_type;
        if ($dbItem->is_expense)
            $data['cost'] = $dbItem->moneyFormatter((float)$itemPureCollection->get('purchase_price') / (1 + (float)($dbItem->vts / 100)));
        else
            $data['cost'] = $dbItem->moneyFormatter((float)$dbItem->cost);

        return $invoice->items()->create($data);
    }


    private function validateItemRequestData(Item $item, $itemPureCollection, $index = 0)
    {
//        echo $item->availableQty() < (int)$itemPureCollection->get('qty') ? 'yes' : "no";
//        exit();
        if ($item->isQtyable() && ($item->availableQty() == 0 || $item->availableQty() < (int)$itemPureCollection->get('qty'))) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "items.{$index}.qty" => ['item qty is not enough'],
            ]);
            throw $error;
        }
        if ($item->isNeedSerial())
            dispatch(new ValidateItemSerialsJob($item, (int)$itemPureCollection->get('qty'), (array)$itemPureCollection->get('serials'), 'sale', $this->availableSerialsStatus, $index));

    }
}
