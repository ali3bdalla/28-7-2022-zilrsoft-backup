<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $childrenList = Account::find($mainAccount->getChildrenIncludeMe());
        foreach ($childrenList as $account) {
            $this->normalize($account);
        }
    }
    private function targetAccount()
    {
        
        $targetAccount = auth()->user()->organization->getConfig("TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT","ACCOUNTING",true);
        
        return Account::find($targetAccount );
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
            'creator_id' => auth()->user()->id,
            'amount' => $amount,
            'organization_id' => auth()->user()->organization_id,
        ]);

        $entity->transactions()->create([
            'account_id' => $sourceAccount->id,
            'amount' => $amount,
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'type' => $sourceTransactionType
        ]);
        $entity->transactions()->create([
            'account_id' => $targetAccount->id,
            'amount' => $amount,
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'type' => $targetTransactionType
        ]);
    }
}
