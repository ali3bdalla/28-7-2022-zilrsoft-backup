<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateCloseYearEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $loggedUser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Manager $loggedUser)
    {
        $this->loggedUser = $loggedUser;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        DB::beginTransaction();
        try {
            $createdDate = $this->getCloseYearDate();
            $totalDebitAmount =  0;
            $totalcreditAmount = 0;


            $closeYearEntity = TransactionsContainer::create([
                'creator_id' =>  $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'amount' => 0,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ]);

            Account::chunk(50, function ($accounts) use ($totalDebitAmount, $totalcreditAmount, $closeYearEntity, $createdDate) {
                foreach ($accounts as $key => $account) {
                    $accountYearBalance = $account->yearlyAccountBalance();
                    if ($accountYearBalance != 0) {
                        if ($account->slug == 'clients' || $account->slug == 'vendors') {
                            $amounts =  $this->createUsersTransactions($closeYearEntity, $account, $accountYearBalance, $createdDate);
                            $totalDebitAmount += (float) $amounts['debit'];
                            $totalcreditAmount += (float) $amounts['credit'];
                        } else if ($account->slug == 'stock') {
                            $amounts =  $this->createItemsTransactions($closeYearEntity, $account, $accountYearBalance, $createdDate);
                            $totalDebitAmount += (float) $amounts['debit'];
                            $totalcreditAmount += (float) $amounts['credit'];
                        } else {
                            $transaction = $this->createTransaction($closeYearEntity, $account, $accountYearBalance, $createdDate);

                            if ($transaction->type == 'debit') {
                                $totalDebitAmount += (float) $transaction->amount;
                            } else {
                                $totalcreditAmount += (float) $transaction->amount;
                            }
                        }
                    }
                }
            });
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            throw $ex;
        }
    }

    private function createTransaction(TransactionsContainer $entity, Account $account, $balance, $createdDate, $options = [])
    {
        $transactionType = $account->type;
        if ($balance < 0) {
            $transactionType = $account->type == 'debit' ? 'credit'  : 'debit';
        }

        // return Transaction::withoutEvents(function() use ($entity,$account, $balance, $createdDate, $transactionType,$options){
        return $transaction = $entity->transactions()->create(array_merge($options, [
            'account_id' => $account->id,
            'amount' => abs($balance),
            'creator_id' => $this->loggedUser->id,
            'organization_id' => $this->loggedUser->organization_id,
            'type' => $transactionType,
            'created_at' => $createdDate,
            'updated_at' => $createdDate
        ]));
        // dispatch_now(new UpdateAccountBalanceJob($transaction));

        //     return $transaction;
        // });


    }



    public function createUsersTransactions(TransactionsContainer $entry, Account $account, $balance, $createdDate)
    {
        $totalDebitAmount =  0;
        $totalcreditAmount = 0;
        if ($account->slug === 'clients') {
            User::where('is_client', true)->chunk(50, function ($users) use ($account, $totalDebitAmount, $totalcreditAmount, $entry, $createdDate) {
                foreach ($users as $user) {
                    $balance =  $user->yearlyUserBalance($account);
                    $transaction = $this->createTransaction($entry, $account, $balance, $createdDate, ['user_id' => $user->id]);
                    if ($transaction->type == 'debit') {
                        $totalDebitAmount += (float) $transaction->amount;
                    } else {
                        $totalcreditAmount += (float) $transaction->amount;
                    }
                }
            });
        } else {
            User::where('is_vendor', true)->chunk(50, function ($users) use ($account, $totalDebitAmount, $totalcreditAmount, $entry, $createdDate) {
                foreach ($users as $user) {
                    $balance =  $user->yearlyUserBalance($account);
                    $transaction = $this->createTransaction($entry, $account, $balance, $createdDate, ['user_id' => $user->id]);
                    if ($transaction->type == 'debit') {
                        $totalDebitAmount += (float) $transaction->amount;
                    } else {
                        $totalcreditAmount += (float) $transaction->amount;
                    }
                }
            });
        }




        return [
            'debit' => $totalDebitAmount,
            'credit' => $totalcreditAmount
        ];
    }




    public function createItemsTransactions(TransactionsContainer $entry, Account $account, $balance, $createdDate)
    {

        $totalDebitAmount =  0;
        $totalcreditAmount = 0;
        Item::where('is_kit', false)->chunk(50, function ($items) use ($account, $totalDebitAmount, $totalcreditAmount, $entry, $createdDate) {
            foreach ($items as $item) {
                $lastEntry =  $item->pipeline()->orderByDesc('id')->first();
                if ($lastEntry) {
                    $balance =  $lastEntry->total_stock_cost_amount;
                } else {
                    $balance = 0;
                }
                $transaction = $this->createTransaction($entry, $account, $balance, $createdDate, ['item_id' => $item->id]);
                if ($transaction->type == 'debit') {
                    $totalDebitAmount += (float) $transaction->amount;
                } else {
                    $totalcreditAmount += (float) $transaction->amount;
                }
            }
        });

        return [
            'debit' => $totalDebitAmount,
            'credit' => $totalcreditAmount
        ];
    }





    private function getCloseYearDate()
    {
        return Carbon::parse('2021-01-01');
    }
}
