<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
use App\Models\Account;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NormalizeIncomesExpensesJob implements ShouldQueue
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
        //

        DB::beginTransaction();
        try {

            $incomesExpeneseAccount = Account::where([
                ['parent_id', 0]
            ])->whereIn('slug', ['expenses', "income"])->get();

            foreach ($incomesExpeneseAccount as $mainAccount) {
                $this->normalizeList($mainAccount);
            }
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            throw $ex;
        }
    }

    public function normalizeList(Account $mainAccount)
    {
        $accounts = $mainAccount->getChildrenIncludeMe();


        foreach ($accounts as $accountId) {
            $account = Account::find($accountId);
            if($account)
                $this->normalize($account);
        }

        // $childrenList = Account::find($mainAccount->getChildrenIncludeMe());
        // foreach ($childrenList as $account) {
        //     $this->normalize($account);
        // }
    }
    private function targetAccount()
    {
        
        $targetAccount = $this->loggedUser->organization->getConfig("TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT","ACCOUNTING",true);
        
        return Account::findOrFail($targetAccount );
    }
    private function normalize(Account $account)
    {

        $yearlyBalance = $account->yearlyAccountBalance();
        if ($yearlyBalance != 0) {
            $transactionType = $account->type;
            if ($yearlyBalance > 0) {
                $transactionType = $account->type == 'debit' ? 'credit'  : 'debit';
            }
            $targetTransactionType = $transactionType == 'debit' ? 'credit'  : 'debit';
            $targetAccount = $this->targetAccount();
            $amount = abs($yearlyBalance);
            $this->createNormalizationEntity($amount, $account, $targetAccount, $transactionType, $targetTransactionType);
        }
    }


    private function createNormalizationEntity($amount, $sourceAccount, $targetAccount, $sourceTransactionType, $targetTransactionType)
    {
        $entity = TransactionsContainer::create([
            'creator_id' => $this->loggedUser->id,
            'amount' => $amount,
            'organization_id' => $this->loggedUser->organization_id,
        ]);

        Transaction::withoutEvents(function() use ($amount,$entity,$sourceAccount, $targetAccount, $sourceTransactionType, $targetTransactionType) {
            $transaction1 = $entity->transactions()->create([
                'account_id' => $sourceAccount->id,
                'amount' => $amount,
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'type' => $sourceTransactionType
            ]);

            $transaction2 =  $entity->transactions()->create([
                'account_id' => $targetAccount->id,
                'amount' => $amount,
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'type' => $targetTransactionType
            ]);
            dispatch_now(new UpdateAccountBalanceJob($transaction1));
            dispatch_now(new UpdateAccountBalanceJob($transaction2));
        });
       
    }
}
