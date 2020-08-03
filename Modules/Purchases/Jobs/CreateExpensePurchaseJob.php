<?php

namespace Modules\Purchases\Jobs;

use App\Invoice;
use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Accounting\Jobs\CreatePurchasesEntityTransactionsJob;

class CreateExpensePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var array
     */
    private $requestData;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param array $requestData
     */
    public function __construct(Item $item,$requestData = [])
    {
        //
        $this->item = $item;
        $this->requestData = $requestData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authUser = auth()->user();
        $itemData = collect($this->requestData);
        $dbData = collect($this->item)->toArray();
        $dbData['net'] = $this->item->moneyFormatter($itemData->get('purchase_price'));
        $dbData['subtotal'] = $this->item->moneyFormatter((float)$dbData['net'] / (1 + ($this->item->vtp / 100))); // 0.05 + 1 = 1.05
        $dbData['qty'] = 1;
        $dbData['tax'] = $this->item->moneyFormatter((float)$dbData['net'] - (float)$dbData['subtotal']);
        $dbData['discount'] = 0;
        $dbData['total'] = $this->item->moneyFormatter($dbData['subtotal']);
        $dbData['cost'] = $this->item->moneyFormatter((float)$itemData->get('purchase_price'));
        $dbData['purchase_price'] = $this->item->moneyFormatter((float)$dbData['total']);
        $invoice = Invoice::create([
            'invoice_type' => 'purchase',
            'notes' =>  "",
            'creator_id' => $authUser->id,
            'organization_id' => $authUser->organization_id,
            'branch_id' => $authUser->branch_id,
            'department_id' => $authUser->department_id,
            'parent_invoice_id' => 0,
            'is_deleted' => false
        ]);
        $invoice->purchase()->create([
            'receiver_id' => $authUser->id,
            'vendor_id' => $this->item->expense_vendor_id,
            'organization_id' => $authUser->organization_id,
            'vendor_inc_number' => uniqid(),
            'invoice_type' => 'purchase',
            "prefix" => "PU-"
        ]);
        dispatch(new CreatePurchaseItemsJob($invoice,[$dbData]));
        dispatch(new UpdatePurchasesInvoiceTotalsJob($invoice));
        dispatch(new CreatePurchasesEntityTransactionsJob($invoice,[],[]));
        return $invoice;
    }
}
