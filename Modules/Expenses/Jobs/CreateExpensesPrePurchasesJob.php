<?php

namespace Modules\Expenses\Jobs;

use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Purchases\Jobs\CreateExpensePurchaseJob;

class CreateExpensesPrePurchasesJob implements ShouldQueue
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
        if ($this->expensesItemsArray != null){
            foreach ($this->expensesItemsArray as $expense)
            {
                $dbItem = $dbItem = Item::findOrFail($expense['id']);
                dispatch(new CreateExpensePurchaseJob($dbItem,$expense));
            }
        }

    }


    public function setExpenseItems()
    {

        foreach ($this->itemsArray as $item)
        {
//            dd($item);
            $dbItem = Item::findOrFail($item['id']);
//            dd($dbItem);
            if($dbItem->isExpense())
            {
                $this->expensesItemsArray[] =  $item;
            }
        }
    }
}
