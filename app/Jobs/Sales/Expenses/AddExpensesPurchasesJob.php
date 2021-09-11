<?php

namespace App\Jobs\Sales\Expenses;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Purchases\Jobs\CreateExpensePurchaseJob;

class AddExpensesPurchasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $itemsArray;

    private $expensesItemsArray;
    /**
     * Create a new job instance.
     *
     * @param array $itemsArray
     */
    public function __construct($itemsArray = [])
    {
        $this->itemsArray = $itemsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setExpenseItems();
        if ($this->expensesItemsArray != null) {
            foreach ($this->expensesItemsArray as $expense) {
                $dbItem = $dbItem = Item::findOrFail($expense['id']);
                dispatch_sync(new CreateExpensePurchaseJob($dbItem, $expense));
            }
        }

    }

    public function setExpenseItems()
    {

        foreach ($this->itemsArray as $item) {
            $dbItem = Item::findOrFail($item['id']);
            if ($dbItem->isExpense()) {
                $this->expensesItemsArray[] = $item;
            }
        }
    }




}
