<?php

namespace App\Jobs\Sales\Expense;

use App\Jobs\Accounting\Purchase\StorePurchaseTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Purchases\Items\StorePurchaseItemsJob;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatePurchaseInvoiceForExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var array
     */
    private $items;
    /**
     * @var array
     */
    private $expenses;

    /**
     * Create a new job instance.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        //
        $this->items = $items;
        $this->expenses = $this->getExpenses();
    }

    public function getExpenses()
    {

        $expenses = [];
        foreach ($this->items as $item) {
            $dbItem = Item::findOrFail($item['id']);
            if ($dbItem->is_expense) {
                $expenses [] = $item;
            }
        }

        return $expenses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->expenses != null) {
            foreach ($this->expenses as $expense) {
                $dbItem = $dbItem = Item::findOrFail($expense['id']);
                $this->createExpenseInvoice($dbItem, $expense);
            }
        }
    }

    private function createExpenseInvoice(Item $item, $requestData)
    {
        $authUser = auth()->user();
        $itemData = collect($requestData);

        $dbData = collect($item)->toArray();
        if($itemData->has('qty'))
        {
            $qty = $itemData->get('qty');
        }else{
            $qty = 1;
        }
        $dbData['qty'] = (int)$qty;
        $dbData['net'] = (float)$itemData->get('purchase_price') * (int)$qty;
        $dbData['subtotal'] = (float)$dbData['net'] / (1 + ($item->vtp / 100)); // 0.05 + 1 = 1.05

        $dbData['tax'] = (float)$dbData['net'] - (float)$dbData['subtotal'];
        $dbData['discount'] = 0;
        $dbData['total'] = $dbData['subtotal'];
        $dbData['cost'] = (float)$itemData->get('purchase_price');
        $dbData['purchase_price'] = (float)$dbData['total'] / (int)$qty;
//        dd($dbData);

        $invoice = Invoice::create([
            'invoice_type' => 'purchase',
            'notes' => "",
            'creator_id' => $authUser->id,
            'organization_id' => $authUser->organization_id,
            'branch_id' => $authUser->branch_id,
            'department_id' => $authUser->department_id,
            'user_id' => $item->expense_vendor_id,
            'managed_by_id' => $authUser->id
        ]);
        $invoice->purchase()->create([
            'receiver_id' => $authUser->id,
            'vendor_id' => $item->expense_vendor_id,
            'organization_id' => $authUser->organization_id,
            'vendor_invoice_id' => uniqid(),
            'invoice_type' => 'purchase',
            "prefix" => "PU-"
        ]);

        dispatch_now(new UpdateInvoiceNumberJob($invoice, 'PU-'));
        dispatch_now(new StorePurchaseItemsJob($invoice, [$dbData]));
        dispatch_now(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
        dispatch_now(new StorePurchaseTransactionsJob($invoice->fresh()));
        return $invoice;
    }
}
