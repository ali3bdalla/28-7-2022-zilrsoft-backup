<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateCloseYearUsersTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TransactionsContainer
     */
    private $transactionsContainer;
    /**
     * @var Manager
     */
    private $loggedUser;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $transactionsContainer
     * @param Manager $loggedUser
     */
    public function __construct(TransactionsContainer $transactionsContainer, Manager $loggedUser)
    {
        //
        $this->transactionsContainer = $transactionsContainer;
        $this->loggedUser = $loggedUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        DB::transaction(function(){
            Account::whereIn('slug', ['vendors', 'clients'])->each(function ($account) {
                if ($account->slug === 'clients') {
                    User::where('is_client', true)->each(function ($user) use ($account) {
                        $this->createUserTransaction($account, $user);
                    });
                } else {
                    User::where('is_vendor', true)->each(function ($user) use ($account) {
                        $this->createUserTransaction($account, $user);
                    });
                }
            });
        });
    }

    private function createUserTransaction($account, User $user)
    {
        $balance = $this->getUserBalance($account,$user);
        if ($balance != 0) {
            CreateCloseYearTransactionJob::dispatchNow($this->transactionsContainer, $account, $balance, $this->loggedUser, ['user_id' => $user->id]);
        }
    }

    public function getUserBalance($account,$user)
    {
        $creditAmount =Transaction::whereDate('created_at','<',Carbon::parse($this->transactionsContainer->created_at)->toDate())->where([
            ['user_id',$user->id],
            ['account_id',$account->id],
            ['type','credit']
        ])->sum('amount');
        $debitAmount =Transaction::whereDate('created_at','<',Carbon::parse($this->transactionsContainer->created_at)->toDate())->where([
            ['user_id',$user->id],
            ['account_id',$account->id],
            ['type','debit']
        ])->sum('amount');
        if($account->type == 'credit') return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }
}
