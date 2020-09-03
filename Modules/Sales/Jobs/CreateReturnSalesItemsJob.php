<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use App\InvoiceItems;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Jobs\CreateReturnSalesItemEntityJob;
use Modules\Items\Jobs\ChangeItemSerialsStatusJob;
use Modules\Items\Jobs\EnsureReturnedItemQtyIsStillMoreThanOrEqualToZeroJob;
use Modules\Items\Jobs\UpdateInvoiceItemProfitJob;
use Modules\Items\Jobs\UpdateItemCostJob;
use Modules\Items\Jobs\UpdateItemQtyJob;
use Modules\Items\Jobs\UpdateItemReturnedQtyJob;
use Modules\Items\Jobs\UpdateKitDataDependingOnItemsJob;
use Modules\Items\Jobs\ValidateItemSerialsJob;

class CreateReturnSalesItemsJob implements ShouldQueue
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


    private $availableSerialsStatus = ["saled"];
    /**
     * @var Invoice|null
     */
    private $parentInvoice;
    /**
     * @var Invoice|null
     */
    private $masterInvoice;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $requestItemsData
     * @param Invoice|null $masterInvoice
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice, $requestItemsData = [], Invoice $masterInvoice = null)
    {
        //
        $this->requestItemsData = (array)$requestItemsData;
        $this->invoice = $invoice;
        $this->entity = $entity;
        $this->masterInvoice = $masterInvoice;
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
            $invoiceItem = InvoiceItems::findOrFail($itemRequestData['id']);
            if ($invoiceItem->item->isKit())
                $this->createKit($this->invoice, $invoiceItem, $itemRequestData, $index);
            elseif(!$invoiceItem->isBelongToKit())
                $this->createItem($invoiceItem, $itemRequestData, $index);

            dispatch(new EnsureReturnedItemQtyIsStillMoreThanOrEqualToZeroJob($invoiceItem));
        }
    }

    public function createKit(Invoice $invoice, InvoiceItems $dbInvoiceItem, $itemRequestData, $index)
    {
        $itemPureCollection = collect($itemRequestData);
        $qty = (int)$itemPureCollection->get('returned_qty');
        $showInPrint = (bool)$itemPureCollection->get('printable');
        if (!$showInPrint)
            $invoice->update(['printable_price' => false]);

        $data['belong_to_kit'] = false;
        $data['printable'] = $showInPrint;
        $data['parent_kit_id'] = 0;
        $data['discount'] = $dbInvoiceItem->moneyFormatter((float)(($dbInvoiceItem->discount / $dbInvoiceItem->qty) * $qty));
        $data['price'] = (float)$dbInvoiceItem->total;
        $data['qty'] = $qty;
        $data['total'] = $dbInvoiceItem->moneyFormatter($data['price'] * $data['qty']);
        $data['subtotal'] = $dbInvoiceItem->moneyFormatter((float)$data['total'] - (float)$data['discount']);
        $data['tax'] = $dbInvoiceItem->moneyFormatter($dbInvoiceItem->item->getSaleTax($data['subtotal']));
        $data['net'] = $dbInvoiceItem->moneyFormatter((float)$data['subtotal'] + (float)$data['tax']);
        $data['organization_id'] = $invoice->organization_id;
        $data['creator_id'] = $invoice->creator_id;
        $data['item_id'] = $dbInvoiceItem->item->id;
        $data['user_id'] = $invoice->user_id;
        $data['invoice_type'] = $invoice->invoice_type;
        $data['is_kit'] = true;
        $invoiceKitItem = $invoice->items()->create($data);
        $this->createKitItems($itemPureCollection, $dbInvoiceItem, $invoice, $invoiceKitItem, $qty, $index);
        dispatch(new UpdateKitDataDependingOnItemsJob($invoiceKitItem));

    }

    public function createKitItems($itemPureCollection, InvoiceItems $oldDbKit, Invoice $invoice, InvoiceItems $baseItem, $qty, $index)
    {


        $oldKitsItems = $this->masterInvoice->items()->belongToKit($oldDbKit)->get();
        foreach ($oldKitsItems as $oldKitsItem)
        {
            if ($oldKitsItem->item->isNeedSerial()) {
                $data = collect($itemPureCollection->get('items'))->where('id',$oldKitsItem->id)->first();
                if (!empty($data)) {
                    $sendData['serials'] = $data['serials'];
                } else {
                    $sendData['serials'] = [];
                }
            }
            $sendData['returned_qty'] = (int)($oldKitsItem->qty / $oldDbKit->qty) * $qty;
            $sendData['belong_to_kit'] = true;
            $sendData['kit_id'] = $baseItem->id;
            $sendData['printable'] = $oldKitsItem->printable;
            $this->createItem($oldKitsItem, $sendData, $index);
        }

    }

    public function createItem(InvoiceItems $invoiceItem, $itemRequestData, $index)
    {
        $createdInvoiceItem = $this->addPureItem($this->invoice, $invoiceItem, $itemRequestData, $index);
        if ($createdInvoiceItem->item->isQtyable() && !$this->invoice->isPending()) {
            dispatch(new UpdateItemCostJob($this->invoice, $createdInvoiceItem));
            dispatch(new UpdateItemQtyJob($this->invoice, $createdInvoiceItem));
            dispatch(new CreateReturnSalesItemEntityJob($this->entity, $this->invoice, $createdInvoiceItem));
            if ($createdInvoiceItem->item->isNeedSerial())
                dispatch(new ChangeItemSerialsStatusJob($createdInvoiceItem->item,$this->invoice, $itemRequestData['serials'], $this->availableSerialsStatus, 'r_sale'));
            dispatch(new UpdateInvoiceItemProfitJob($createdInvoiceItem));
        }
        dispatch(new UpdateItemReturnedQtyJob($invoiceItem, (int)$itemRequestData['returned_qty']));
    }


    public function addPureItem(Invoice $invoice, InvoiceItems $dbInvoiceItem, $itemRequestData, $index = 0)
    {
        $itemPureCollection = collect($itemRequestData);
        $this->validateItemRequestData($dbInvoiceItem, $itemPureCollection, $index); // validate Item
        $showInPrint = (bool)$itemPureCollection->get('printable');
        if (!$showInPrint)
            $invoice->update(['printable_price' => false]);
        $data['printable'] = $showInPrint;
        $belongToKit = (bool)$itemPureCollection->get('belong_to_kit');

        $data['parent_kit_id'] = $belongToKit? (int)$itemPureCollection->get('kit_id') : 0;
        $data['belong_to_kit'] = $belongToKit;
        $data['discount'] = $dbInvoiceItem->moneyFormatter(($dbInvoiceItem->discount / $dbInvoiceItem->qty) * (float)$itemPureCollection->get('returned_qty'));
        $data['price'] = $dbInvoiceItem->price;
        $data['qty'] = (int)$itemPureCollection->get('returned_qty');
        $data['total'] = $dbInvoiceItem->moneyFormatter((float)$data['price'] * (int)$data['qty']);
        $data['subtotal'] = $dbInvoiceItem->moneyFormatter((float)$data['total'] - (float)$data['discount']);
        $data['tax'] = $dbInvoiceItem->moneyFormatter($dbInvoiceItem->item->getSaleTax($data['subtotal']));
        $data['net'] = $dbInvoiceItem->moneyFormatter((float)((float)$data['tax'] + (float)$data['subtotal']));
        $data['organization_id'] = $invoice->organization_id;
        $data['creator_id'] = $invoice->creator_id;
        $data['item_id'] = $dbInvoiceItem->item->id;
        $data['user_id'] = $invoice->user_id;
        $data['invoice_type'] = $invoice->invoice_type;
        $data['cost'] = (float)$dbInvoiceItem->item->cost;
        return $invoice->items()->create($data);
    }


    private function validateItemRequestData(InvoiceItems $invoiceItem, $itemPureCollection, $index = 0)
    {
        $returnedQty = (int)$invoiceItem->r_qty + (int)$itemPureCollection->get('returned_qty');
        if ($returnedQty > $invoiceItem->qty) {
            // dd($returnedQty , $invoiceItem->qty);
            $error = ValidationException::withMessages([
                "items.{$index}.returned_qty" => ['item qty is not enough'],
            ]);
            throw $error;
        }
        if ($invoiceItem->item->isNeedSerial())
            dispatch(new ValidateItemSerialsJob($invoiceItem->item, (int)$itemPureCollection->get('returned_qty'), (array)$itemPureCollection->get('serials'), 'return_sale', $this->availableSerialsStatus, $index, $this->masterInvoice));



    }
}
