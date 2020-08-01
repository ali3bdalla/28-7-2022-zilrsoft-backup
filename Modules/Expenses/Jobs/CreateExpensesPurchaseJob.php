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

    private $invoice;
    /**
     * @var array
     */
    private $expensesIds;

    /**
     * Create a new job instance.
     *
     * @param $invoice
     * @param array $expensesIds
     */
    public function __construct($expensesIds = [],$invoice = null)
    {

        $this->invoice = $invoice;
        $this->expensesIds = $expensesIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($this->expensesIds as $id)
        {
//            $items
        }
    }

}
