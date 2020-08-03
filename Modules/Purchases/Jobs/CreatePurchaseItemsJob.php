<?php

namespace Modules\Purchases\Jobs;

use App\Invoice;
use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Accounting\Jobs\CreatePurchasesItemsEntityJob;
use Modules\Items\Jobs\CreateItemSerialsJob;
use Modules\Items\Jobs\UpdateItemCostJob;
use Modules\Items\Jobs\UpdateItemLastPurchasePriceJob;
use Modules\Items\Jobs\UpdateItemQtyJob;
use Modules\Items\Jobs\UpdateItemSalesPriceJob;
use Modules\Items\Jobs\ValidateItemSerialsJob;

class CreatePurchaseItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var array
     */
    private $items;
    /**
     * @var array
     */
    private $expenses;
    /**
     * @var array
     */
    private $paymentsMethods;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param array $items
     * @param array $expenses
     * @param $paymentsMethods
     */
    public function __construct(Invoice $invoice,$items = [],$paymentsMethods = [],$expenses = [])
    {
        //
        $this->invoice = $invoice;
        $this->items = $items;
        $this->expenses = $expenses;
        $this->paymentsMethods = $paymentsMethods;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->items as $index => $item)
        {
            $dbItem = Item::findOrFail($item['id']);
            $collectionData = collect($item);
            if($dbItem->isNeedSerial())
            {
                dispatch(new ValidateItemSerialsJob($dbItem,$collectionData->get('qty'),(array)$collectionData->get('serials'),'purchase',['purchase','r_sale','available'],$index));
            }
            if($dbItem->isCostableItem())
            {
                $invoiceItem = $this->createItem($dbItem,$collectionData);
                if(!empty($this->expenses))
                    $totalItemExpenseAmount = $this->addExpenseToInvoiceItem($dbItem,$collectionData);
                else
                    $totalItemExpenseAmount = 0;
                if($dbItem->isNeedSerial())
                    dispatch(new CreateItemSerialsJob($invoiceItem,(array)$collectionData->get('serials')));

                dispatch(new UpdateItemQtyJob($this->invoice,$invoiceItem));
                dispatch(new UpdateItemCostJob($this->invoice,$invoiceItem));
                if(!$this->invoice->isPending())
                {
                    dispatch(new UpdateItemLastPurchasePriceJob($invoiceItem));
                    dispatch(new UpdateItemSalesPriceJob($invoiceItem,$collectionData->get('price_with_tax')));
                }
                dispatch(new CreatePurchasesItemsEntityJob($this->invoice,$invoiceItem,$totalItemExpenseAmount));
            }

        }
    }


    public function createItem(Item $dbItem,$collectionData )
    {
        $authUser = auth()->user();
        $data = [];
        if ($dbItem->isExpense())
            $data['cost'] = $collectionData->get('purchase_price');
        else
            $data['cost'] = $dbItem->cost;
        $data['price'] = $collectionData->get('purchase_price');
        $data['invoice_type'] = 'purchase';
        $data['user_id'] = $this->invoice->user_id;
        $data['qty'] = (int) $collectionData->get('qty');
        $data['discount'] = (float) $collectionData->get('discount');
        $data['total'] = $dbItem->moneyFormatter((float)$data['price'] * (int)$data['qty']);
        $data['subtotal'] = $dbItem->moneyFormatter((float)$data['total'] - (float)$data['discount']);
        $data['tax'] = $dbItem->moneyFormatter($dbItem->getSaleTax($data['subtotal']));
        $data['net'] = $dbItem->moneyFormatter((float) ((float)$data['tax'] + (float)$data['subtotal']));
        $data['organization_id'] = $authUser->organization_id;
        $data['creator_id'] = $authUser->id;
        $data['item_id'] = $dbItem->id;
        return $this->invoice->items()->create($data);

    }

    public function addExpenseToInvoiceItem(Item $dbItem,$collectionData)
    {
        $totalExpenseAmount = 0;
        foreach ($this->expenses as $expense){
            $amount =(float)$expense['amount'] * (float)$collectionData->get('widget') / (float)$dbItem->getPurchaseTaxAsFloatValue(); //
            $totalExpenseAmount+=(float)$amount;
             $dbItem->expenses()->create([
                 'amount' => $amount,
                 'invoice_id' => $this->invoice->id,
                 'expense_id' => $expense['id'],
                 'creator_id' => auth()->user()->id,
                 'organization_id' => auth()->user()->organization_id,
                 'is_paid' => $expense['is_apended_to_net']
             ]);
        }
        return $totalExpenseAmount;
    }
}
