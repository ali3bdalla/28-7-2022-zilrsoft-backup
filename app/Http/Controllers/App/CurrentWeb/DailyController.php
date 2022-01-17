<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\ResellerClosingAccount;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\ManagerDailyWalletRepositoryContract;
use App\Repository\ManagerRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    private AccountsDailyRepositoryContract $accountsDailyRepositoryContract;
    private ManagerRepositoryContract $managerRepositoryContract;
    private ManagerDailyWalletRepositoryContract $managerDailyWalletRepositoryContract;

    public function __construct(AccountsDailyRepositoryContract $accountsDailyRepositoryContract, ManagerRepositoryContract $managerRepositoryContract, ManagerDailyWalletRepositoryContract $managerDailyWalletRepositoryContract)
    {
        $this->accountsDailyRepositoryContract = $accountsDailyRepositoryContract;
        $this->managerRepositoryContract = $managerRepositoryContract;
        $this->managerDailyWalletRepositoryContract = $managerDailyWalletRepositoryContract;
    }

    public function resellerClosingAccountsIndex()
    {
        $managerCloseAccountList = ResellerClosingAccount::myDailyCloseAccounts()->orderBy('id', 'desc')->paginate(100);

        return view('accounting.reseller_daily.account_close_list', compact('managerCloseAccountList'));
    }

    public function createResellerClosingAccount(Request $request)
    {
        $loggedUser = $request->user();
        $outAmount = $this->accountsDailyRepositoryContract->getResellerDailyBankOutcomeAmount();
        $inAmount = $this->accountsDailyRepositoryContract->getResellerDailyBankIncomeAmount();
        $remainingAccountsBalanceAmount = $loggedUser->remaining_accounts_balance;
        $accountsClosedAt = $loggedUser->accounts_closed_at;
        $gateways = $loggedUser->gateways()->get();

        return view('accounting.reseller_daily.account_close', compact('inAmount', 'loggedUser', 'accountsClosedAt', 'outAmount', 'gateways', 'remainingAccountsBalanceAmount'));
    }

    public function resellerAccountsTransactionsIndex()
    {
        $managerCloseAccountList = $this->managerDailyWalletRepositoryContract->getWalletTransferPagination();
        return view('accounting.reseller_daily.tranfers_list', compact('managerCloseAccountList'));
    }

    public function createResellerAccountTransaction()
    {
        $manager_gateways = $this->managerRepositoryContract->getCurrentManagerBanks()->append('current_amount');
        $gateways = $this->managerRepositoryContract->getAllManagersBanksExcept([Auth::id()]);
        return view('accounting.reseller_daily.transfer_amounts', compact('gateways', 'manager_gateways'));
    }
}
