<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EnsureSalesDataAreCorrectJob implements ShouldQueue
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
        $this->validateData();
    }

    private function validateData()
    {
        $creditAmount = 0;
        $debitAmount = 0;

        $transactions = $this->invoice->transactions()->where('description', '!=', 'client_balance')->get();
        foreach ($transactions as $transaction) {
            if (!in_array($transaction['description'], [
                'to_cogs', 'to_gateway',
                'to_products_sales_discount', 'to_services_sales_discount',
                'to_other_services_sales_discount', 'to_stock'
            ])) {
                $creditAmount = $creditAmount + $transaction['amount'];
            } else {
                $debitAmount = $debitAmount + $transaction['amount'];
            }
        }

        $def = abs($creditAmount - $debitAmount);
        // throw new Exception($def);

        if ($def !== 0) {

            if ($def < 1) {
                $transaction = $this->invoice->transactions()->where('description', 'to_cogs')->first();
                if ($transaction == null) {
                    Log::error('sales invoice accounting error : ', $this->invoice->load('transactions', 'items.item')->toArray());
                    $error = ValidationException::withMessages([
                        "invoice" => ['credit side not match debit side'],
                    ]);
                } else {
                    $problemAmount = $creditAmount - $debitAmount;
                    $debitableStatistics = $transaction->debitable->_getStatisticsInstance();

                    if ($problemAmount  > 0) {
                        $newAmount =   (float)$transaction->amount + abs((float)$problemAmount);
                        $debtetabielNewAmount =  $debitableStatistics->debit_amount +  abs((float)$problemAmount);
                    } else {
                        $newAmount =   (float)$transaction->amount - abs((float)$problemAmount);
                        $debtetabielNewAmount =  $debitableStatistics->debit_amount -  abs((float)$problemAmount);

                        // $transaction->debitable()->update([
                        //     'amount' => $newAmount
                        // ]);
                    }
                    $transaction->update([
                        'amount' => $newAmount
                    ]);

                    $debitableStatistics->update([
                        'debit_amount' =>    $debtetabielNewAmount
                    ]);


                    // $this->validateData();
                }
            }
        } else {
            Log::error('sales invoice accounting error : ', $this->invoice->load('transactions', 'items.item')->toArray());
            $error = ValidationException::withMessages([
                "invoice" => ['credit side not match debit side'],
            ]);
            throw $error;
            // }
        }
    }
}
