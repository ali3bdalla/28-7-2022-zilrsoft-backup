<?php

namespace Modules\Expenses\Jobs;

use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class createExpensesPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $items = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice, $items)
    {
        $itemsIds = collect($items)->pluck('id');
        $items = Item::find($itemsIds);
        foreach ($items as $item)
            if ($item->isExpense())
                $this->items[] = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

}
