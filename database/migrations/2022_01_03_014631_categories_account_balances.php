<?php

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoriesAccountBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (DB::table('organizations')->get() as $organization) {
            $accounts = DB::table('accounts')->where('organization_id', $organization->id)->get();
            $workingYears = $this->getOrganizationWorkingYears($organization);
            foreach ($accounts as $account) {
                foreach ($workingYears as $workingYear) {
                    $debit = DB::table('transactions')->whereYear('created_at', $workingYear)->where('account_id', $account->id)->where('type', 'debit')->sum('amount');
                    $credit = DB::table('transactions')->whereYear('created_at', $workingYear)->where('account_id', $account->id)->where('type', 'credit')->sum('amount');
                    if ($account->type === 'credit') {
                        $balance = $credit - $debit;
                    } else {
                        $balance = $debit - $credit;
                    }
                    DB::table('annual_balances')->insert([
                        'account_type' => basename(Account::class),
                        'account_id' => $account->id,
                        'debit' => $debit,
                        'credit' => $credit,
                        'balance' => $balance,
                        'year' => $workingYear
                    ]);
                }
            }
        }

    }

    private function getOrganizationWorkingYears($organization): array
    {
        return range(Carbon::parse($organization->created_at)->year, Carbon::now()->year);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
