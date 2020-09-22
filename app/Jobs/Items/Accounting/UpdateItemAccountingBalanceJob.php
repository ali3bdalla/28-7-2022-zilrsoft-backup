<?php

namespace App\Jobs\Items\Accounting;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemAccountingBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $item,$amount,$type;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param $amount
     * @param string $type
     */
    public function __construct(Item $item,$amount,$type = 'debit')
    {
        $this->amount = $amount;
        $this->type = $type;
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 'credit')
        {
            $creditAmount = $this->item->total_credit_amount + $this->amount;
            $this->item->update([
                'total_credit_amount' => $creditAmount
            ]);
        }else
        {
            $debitAmount = $this->item->total_debit_amount + $this->amount;
            $this->item->update([
                'total_debit_amount' => $debitAmount
            ]);
        }
    }
}
