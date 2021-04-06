<?php

namespace App\Console\Commands\Accounting;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyUpdateAccountSnapshotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_daily_update_accounts_snapshots_command';

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
//        DB::transaction(function () {
        DB::table('account_snapshots')->delete();

        $transactions = DB::select("select * from transactions where is_pending = false and deleted_at is null order by created_at");
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
        }

//            $transactionsAmounts = DB::select("
//                                select sum(case when type = 'debit'  then amount  else 0 end) as total_debit,
//                                sum(case when type = 'credit'  then amount  else 0 end) as total_credit,
//                                  account_id, date(created_at) as snapshot_date, organization_id
//                                from transactions
//                                where is_pending = false and deleted_at is null
//                                group by date(created_at),account_id,organization_id");
//
//            foreach ($transactionsAmounts as $snapshotAmount) {
//                $date = Carbon::parse($snapshotAmount->snapshot_date);
//
//                DB::table('account_snapshots')->insert([
//                    'created_at' => $date,
//                    'updated_at' => $date,
//                    'account_id' => $snapshotAmount->account_id,
//                    'organization_id' => $snapshotAmount->organization_id,
//                    'credit_amount' => $snapshotAmount->total_credit,
//                    'debit_amount' => $snapshotAmount->total_debit,
//                ]);
//            }

//        });
    }

    private function addTransaction($transaction)
    {
        $account = DB::table('accounts')->find($transaction->account_id);
        $date = Carbon::parse($transaction->created_at)->toDate();
        $amount = moneyFormatter($transaction->amount);
        if ($account) {
            $snapshot = DB::table('account_snapshots')
                ->whereDate('created_at', $date)
                ->where('account_id', $account->id)->first();
            if (!$snapshot) {
                $snapshotId = DB::table('account_snapshots')->insertGetId([
                    'created_at' => $date,
                    'updated_at' => $date,
                    'account_id' => $account->id,
                    'organization_id' => $account->organization_id,
                    'credit_amount' => 0,
                    'debit_amount' => 0,
                ]);

                $snapshot = DB::table('account_snapshots')->find($snapshotId);
            }



            DB::table('transactions')->where('id', $transaction->id)->update([
                'total_debit_amount' => DB::table('account_snapshots')->where([
                    ['account',$account->id],
                    ['created_at','<=',$date]
                ])->sum('debit_amount'),
                'total_credit_amount' => DB::table('account_snapshots')->where([
                    ['account',$account->id],
                    ['created_at','<=',$date]
                ])->sum('credit_amount'),
            ]);


            if ($transaction->type === 'debit') {
                DB::table('account_snapshots')->where('id', $snapshot->id)->update([
                    'debit_amount' => DB::raw("debit_amount + {$amount}")
                ]);
            } else {
                DB::table('account_snapshots')->where('id', $snapshot->id)->update([
                    'credit_amount' => DB::raw("credit_amount + {$amount}")
                ]);
            }

        }
    }
}
