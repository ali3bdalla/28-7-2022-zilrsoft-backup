<?php

namespace Tests\Feature\App;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTransactionsBalanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_transactions_amount()
    {

        $transactions = Transaction::orderBy('created_at','desc')->get();

        Account::where('id','!=',0)->update([
            'total_credit_amount' => 0,
            'total_debit_amount' => 0,
        ]);
        foreach ($transactions as $transaction)
        {

            $account = $transaction->account;
            if ($transaction->type == 'credit') {
                $totalCreditAmount = $account->total_credit_amount + (float)$transaction->amount;
                $totalDebitAmount = $account->total_debit_amount;

                $account->update([
                    'total_credit_amount' => $totalCreditAmount,
                ]);


            } else {
                $totalDebitAmount = $account->total_debit_amount + (float)$transaction->amount;
                $totalCreditAmount = $account->total_credit_amount;

                $account->update([
                    'total_debit_amount' => $totalDebitAmount,
                ]);
            }

            $transaction->update([
                'total_debit_amount' => $totalDebitAmount,
                'total_credit_amount' => $totalCreditAmount,
            ]);

        }

    }
}
