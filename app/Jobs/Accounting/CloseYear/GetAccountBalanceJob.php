<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAccountBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    private $account;
    private $beforeDate;

    /**
     * Create a new job instance.
     *
     * @param Account $account
     * @param $beforeDate
     */
    public function __construct(Account $account,$beforeDate)
    {
        //
        $this->account = $account;
        $this->beforeDate = $beforeDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $creditAmount = $this->account->snapshots()->whereDate('created_at','<',Carbon::parse($this->beforeDate)->toDate())->sum('credit_amount');
        $debitAmount = $this->account->snapshots()->whereDate('created_at','<',Carbon::parse($this->beforeDate)->toDate())->sum('debit_amount');
        if($this->account->type == 'credit') return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }
}
