<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\ResellerClosingAccount;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\ManagerRepositoryContract;
use App\Scopes\DraftScope;
use App\Scopes\PendingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    private AccountsDailyRepositoryContract $accountsDailyRepositoryContract;
    private ManagerRepositoryContract $managerRepositoryContract;

    public function __construct(AccountsDailyRepositoryContract $accountsDailyRepositoryContract, ManagerRepositoryContract $managerRepositoryContract)
    {
        $this->accountsDailyRepositoryContract = $accountsDailyRepositoryContract;
        $this->managerRepositoryContract = $managerRepositoryContract;
    }

    public function resellerClosingAccountsIndex()
    {
        $managerCloseAccountList = ResellerClosingAccount::myDailyCloseAccounts()->orderBy('id', 'desc')->paginate(100);

        return view('accounting.reseller_daily.account_close_list', compact('managerCloseAccountList'));
    }

    public function createResellerClosingAccount(Request $request)
    {
        $loggedUser = $request->user();
        $inAmount = $this->accountsDailyRepositoryContract->getResellerDailyBankIncomeAmount();
        $outAmount = $this->accountsDailyRepositoryContract->getResellerDailyBankOutcomeAmount();
        $remainingAccountsBalanceAmount = $loggedUser->remaining_accounts_balance;
        $accountsClosedAt = $loggedUser->accounts_closed_at;
        $gateways = $loggedUser->gateways()->get();
        return view('accounting.reseller_daily.account_close', compact('inAmount', 'loggedUser', 'accountsClosedAt', 'outAmount', 'gateways', 'remainingAccountsBalanceAmount'));
    }

    public function resellerAccountsTransactionsIndex()
    {
        $managerCloseAccountList = ResellerClosingAccount::where(
            [
                ['creator_id', Auth::id()],
                ['transaction_type', 'transfer'],
            ]
        )->orWhere('receiver_id', Auth::id())->withoutGlobalScope(PendingScope::class)->orderBy('id', 'desc')->paginate(15);

        return view('accounting.reseller_daily.tranfers_list', compact('managerCloseAccountList'));
    }

    public function createResellerAccountTransaction()
    {
        $manager_gateways = $this->managerRepositoryContract->getCurrentManagerBanks();
        $gateways = $this->managerRepositoryContract->getAllManagersBanksExcept([Auth::id()]);

        return view('accounting.reseller_daily.transfer_amounts', compact('gateways', 'manager_gateways'));
    }

}
