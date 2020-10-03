<?php

namespace App\Jobs\Accounting\Entity;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAccountBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $transaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        //
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account = $this->transaction->account;
        if ($this->transaction->type == 'credit') {
            $totalCreditAmount = $account->total_credit_amount + (float) $this->transaction->amount;
            $totalDebitAmount = $account->total_debit_amount;

            $account->update([
                'total_credit_amount' => $totalCreditAmount,
            ]);

        } else {
            $totalDebitAmount = $account->total_debit_amount + (float) $this->transaction->amount;
            $totalCreditAmount = $account->total_credit_amount;

            $account->update([
                'total_debit_amount' => $totalDebitAmount,
            ]);
        }

        $this->transaction->update([
            'total_debit_amount' => $totalDebitAmount,
            'total_credit_amount' => $totalCreditAmount,
        ]);
    }
}
