<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
use App\Models\Account;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $this->loggedUser->organization->addConfig(false, 'is_nomalizing_expenses_incomes_running', null, 'boolean', 'ACCOUNTING');
            $this->loggedUser->organization->addConfig("success", 'normalizing_expense_incomes_status', null, 'string', 'ACCOUNTING');

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();
            $this->loggedUser->organization->addConfig(false, 'is_nomalizing_expenses_incomes_running', null, 'boolean', 'ACCOUNTING');
            $this->loggedUser->organization->addConfig("fail", 'normalizing_expense_incomes_status', null, 'string', 'ACCOUNTING');
            $this->loggedUser->organization->addConfig($ex->getMessage(), 'normalizing_expense_incomes_error_message', null, 'string', 'ACCOUNTING');
            Log::error($ex->getMessage());
            throw $ex;
        }
    }

    public function normalizeList(Account $mainAccount)
    {
        $accounts = Account::find($mainAccount->getChildrenIncludeMe());

        foreach ($accounts as $account) {
            $this->normalize($account);
        }
    }
    private function targetAccount()
    {

        $targetAccount = $this->loggedUser->organization->getConfig("TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT", "ACCOUNTING", true);

        return Account::findOrFail($targetAccount);
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
        $createdAt = Carbon::parse('2020-12-31');

        $entity = TransactionsContainer::create([
            'creator_id' => $this->loggedUser->id,
            'amount' => $amount,
            'organization_id' => $this->loggedUser->organization_id,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);

        $transaction1 = $entity->transactions()->create([
            'account_id' => $sourceAccount->id,
            'amount' => $amount,
            'creator_id' => $this->loggedUser->id,
            'organization_id' => $this->loggedUser->organization_id,
            'type' => $sourceTransactionType,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);

        $transaction2 =  $entity->transactions()->create([
            'account_id' => $targetAccount->id,
            'amount' => $amount,
            'creator_id' => $this->loggedUser->id,
            'organization_id' => $this->loggedUser->organization_id,
            'type' => $targetTransactionType,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);
    }
}
