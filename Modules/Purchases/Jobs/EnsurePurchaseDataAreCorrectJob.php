<?php

namespace Modules\Purchases\Jobs;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnsurePurchaseDataAreCorrectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        //
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transactions = $this->invoice->transactions()->where('description', '!=', 'vendor_balance')->get();
        $creditAmount = 0;
        $debitAmount = 0;
        foreach ($transactions  as $transaction)
        {
            if(in_array($transaction['description'],['to_tax','to_item']))
            {
                $creditAmount = $creditAmount + $transaction['amount'];
            }else
            {
                $debitAmount = $debitAmount + $transaction['amount'];
            }
        }

        if($this->invoice->moneyFormatter($creditAmount) !== $this->invoice->moneyFormatter($debitAmount))
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "invoice"=> ['credit side not match debit side'],
            ]);
            throw $error;
        }
    }
}
