<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use App\Models\Manager;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @param Manager $loggedUser
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

        DB::transaction(function () {
            $createdDate = $this->getCloseYearDate();
            $closeYearEntity = TransactionsContainer::create([
                'creator_id' => $this->loggedUser->id,
                'description' => 'close_accounting_year',
                'organization_id' => $this->loggedUser->organization_id,
                'amount' => 0,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ]);
            $this->createDirectAccountsTransactions($closeYearEntity);
            CreateCloseYearUsersTransactionsJob::dispatch($closeYearEntity, $this->loggedUser);
            CreateCloseYearInventoryTransactionsJob::dispatch($closeYearEntity, $this->loggedUser);
            foreach (Manager::all() as $manager) {
                $manager->addConfig("2021", 'START_YEAR_AT', null, 'date', 'ACCOUNTING');
                $manager->addConfig("2022", 'END_YEAR_AT', null, 'date', 'ACCOUNTING');
            }
        });
    }

    private function getCloseYearDate()
    {
        return Carbon::parse('2021-01-01');
    }

    private function createDirectAccountsTransactions(TransactionsContainer $closeYearEntity)
    {
        DB::transaction(function () use ($closeYearEntity) {
//
            Account:: whereNotIn('slug', ['vendors', 'clients', 'stock'])->each(function ($account) use ($closeYearEntity) {
                $accountYearBalance = GetAccountBalanceJob::dispatchNow($account, $closeYearEntity->created_at);
                if ($accountYearBalance != 0)
                    CreateCloseYearTransactionJob::dispatchNow($closeYearEntity, $account, $accountYearBalance, $this->loggedUser);
            });
        });
    }

}
