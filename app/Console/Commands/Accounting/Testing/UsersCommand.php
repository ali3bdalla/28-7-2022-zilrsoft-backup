<?php

namespace App\Console\Commands\Accounting\Testing;

use App\Jobs\Accounting\CloseYear\GetAccountBalanceJob;
use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_testing_users';

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

        $debit = 0;
        $credit = 0;
        foreach (Account::where('organization_id', 1)->get() as $account) {
            if ($account->slug === 'stock') {
                foreach (Item::where([
                    ['is_kit', false],
                    ['organization_id', 1]
                ])->with('translations')->get() as $item) {
                    $res = $this->getInventoryProductBalance($account, $item);
                    $debit = $debit + $res['debit'];
                    $credit = $credit + $res['credit'];
                }
            } elseif ($account->slug === 'clients') {
                foreach (User::where([
                    ['is_client', true],
                    ['organization_id', 1]
                ])->withTrashed()->get() as $user) {
                    $res = $this->getUserBalance($account, $user);
                    $debit = $debit + $res['debit'];
                    $credit = $credit + $res['credit'];
                }
            } elseif ($account->slug == 'vendors') {
                foreach (User::where([
                    ['is_vendor', true],
                    ['organization_id', 1]
                ])->get() as $user) {
                    $res = $this->getUserBalance($account, $user);
                    $debit = $debit + $res['debit'];
                    $credit = $credit + $res['credit'];
                }

            } else {
                $res = GetAccountBalanceJob::dispatchNow($account, '2021-01-01');
                $debit = $debit + $res['debit'];
                $credit = $credit + $res['credit'];
            }


        }

        dd($debit, $credit);
    }

    public function getInventoryProductBalance($account, $item)
    {
        $debitAmount = Transaction::where([
            ['item_id', $item->id],
            ['account_id', $account->id],
            ['type', 'debit']
        ])->whereDate('created_at', '<', Carbon::parse('2021-01-01')->toDate())->sum('amount');
        $creditAmount = Transaction::where([
            ['item_id', $item->id],
            ['account_id', $account->id],
            ['type', 'credit']
        ])->whereDate('created_at', '<', Carbon::parse('2021-01-01')->toDate())->sum('amount');
        return [
            'debit' => $debitAmount,
            'credit' => $creditAmount
        ];
    }

    public function getUserBalance($account, $user)
    {
        $creditAmount = $account->transactions()->whereDate('created_at', '<', Carbon::parse('2021-01-01')->toDate())->where([
            ['user_id', $user->id],
            ['type', 'credit']
        ])->sum('amount');
        $debitAmount = $account->transactions()->whereDate('created_at', '<', Carbon::parse('2021-01-01')->toDate())->where([
            ['user_id', $user->id],
            ['type', 'debit']
        ])->sum('amount');

        return [
            'debit' => $debitAmount,
            'credit' => $creditAmount
        ];
    }
}
