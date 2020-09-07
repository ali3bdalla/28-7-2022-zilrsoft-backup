<?php

namespace App\Console\Commands;

use App\Account;
use App\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAccountsBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_accounts_balance_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {




        $accounts = Account::all();
        foreach ($accounts as $account) {
            $account->_updateBalanceUsingTransactions();
        }



        $mainAccounts = Account::where('parent_id', 0)->get();


        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0;
        $accounts = [];
        foreach ($mainAccounts as $mainAccount) {
            $children = Account::whereIn('id', $mainAccount->returnNestedTreeIds($mainAccount))->where([[
                'id', '!=', $mainAccount->id
            ]])->withCount('children')->having('children_count', 0)->get();
            $mainAccountChildren = [];
            foreach ($children as $account) {

                if (!is_null($account->statistics)) {
                    $debitAmount = $account->statistics->debit_amount;
                    $creditAmount = $account->statistics->credit_amount;
                } else {
                    $debitAmount = $account->_getDebitTransactionsAmount();
                    $creditAmount = $account->_getCreditTransactionsAmount();
                }

                if ($debitAmount > 0 || $creditAmount > 0) {
                    if ($account->_isCredit()) {
                        $accountTotalAmount = $creditAmount - $debitAmount;
                        $accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0;
                        $accountDebitBalance = $accountTotalAmount > 0 ? 0 : $accountTotalAmount * -1;
                    } else {
                        $accountTotalAmount = $debitAmount - $creditAmount;
                        $accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * -1 : 0;
                        $accountDebitBalance = $accountTotalAmount < 0 ? 0 : $accountTotalAmount;
                    }

                    $account->credit_amount = $creditAmount;
                    $account->debit_amount = $debitAmount;
                    $account->total_amount = $accountTotalAmount;
                    $account->credit_balance = $accountCreditBalance;
                    $account->debit_balance = $accountDebitBalance;

                    $totalCreditAmount += $creditAmount;
                    $totalDebitAmount += $debitAmount;
                    $totalCreditBalance += $accountCreditBalance;
                    $totalDebitBalance += $accountDebitBalance;
                    $mainAccountChildren[] = $account;
                }
            }
            $mainAccount->mainAccountChildren = $mainAccountChildren;
            $accounts[] = $mainAccount;
        }

        if ($totalCreditAmount !== $totalDebitAmount) {
            $variation = $totalCreditAmount - $totalDebitAmount;
            //            dd($variation);
            $transaction = (Invoice::find(13054))->transactions()->first();
            if ($variation > 0) {
                $transaction->update([
                    'amount' => $transaction->amount - $variation
                ]);
            } else {
                $variation = abs($variation);
                $transaction->update([
                    'amount' => $transaction->amount + $variation
                ]);
            }


            $accounts = Account::all();
            foreach ($accounts as $account) {
                $account->_updateBalanceUsingTransactions();
            }
        }
    }
}
