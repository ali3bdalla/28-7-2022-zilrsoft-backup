<?php

namespace App\Console\Commands\Accounting;

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
        DB::table('account_snapshots')->update([
            'credit_amount' => 0,
            'debit_amount' => 0,
        ]);
        $transactionsAmounts = DB::select("select 
        sum(case when type = 'debit'  then amount  else 0 end) as total_debit,
        sum(case when type = 'credit'  then amount  else 0 end) as total_credit,
        account_id,
        date(created_at) as snapshot_date,
        organization_id
        from transactions group by date(created_at),account_id,organization_id");

        foreach ($transactionsAmounts as $snapshotAmount) {
            DB::table('account_snapshots')->where('account_id',$snapshotAmount->account_id)->whereDate('created_at',$snapshotAmount->snapshot_date)->updateOrInsert([
                'created_at' => $snapshotAmount->snapshot_date,
                'account_id' => $snapshotAmount->account_id,
                'organization_id' => $snapshotAmount->organization_id,
                'credit_amount' => $snapshotAmount->total_debit,
                'debit_amount' => $snapshotAmount->total_credit,
            ]);
        }
    }
}
