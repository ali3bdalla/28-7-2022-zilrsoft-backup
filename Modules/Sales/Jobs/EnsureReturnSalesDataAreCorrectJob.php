<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnsureReturnSalesDataAreCorrectJob implements ShouldQueue
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
        $creditAmount = 0;
        $debitAmount = 0;

        $transactions =  $this->invoice->transactions()->where('description','!=','client_balance')->get();
        foreach ($transactions  as $transaction)
        {
            if(in_array($transaction['description'],['to_cogs','to_gateway',
                'to_products_sales_discount','to_services_sales_discount',
                'to_other_services_sales_discount','to_stock']))
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
