<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\Entity\ActivateEntityJob;
use App\Models\Manager;
use App\Models\ResellerClosingAccount;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\ManagerRepositoryContract;
use Illuminate\Http\RedirectResponse;
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
                ['transaction_type', "transfer"],
            ])->orWhere('receiver_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
        return view('accounting.reseller_daily.tranfers_list', compact('managerCloseAccountList'));
    }

    public function createResellerAccountTransaction()
    {
        $manager_gateways = $this->managerRepositoryContract->getCurrentManagerBanks();
        $gateways = $this->managerRepositoryContract->getAllManagersBanksExcept([Auth::id()]);
        return view('accounting.reseller_daily.transfer_amounts', compact('gateways', 'manager_gateways'));
    }

    public function confirmResellerAccountTransaction($transaction): RedirectResponse
    {
        $transaction = ResellerClosingAccount::where([["id", $transaction], ['receiver_id', Auth::id()], ['transaction_type', 'transfer']])->withoutGlobalScope('pending')->firstOrFail();
        $container = $transaction->container;
        dispatch_sync(new ActivateEntityJob($container));
        $transaction->update(['is_pending' => false,]);
        $creator = Manager::find($transaction->creator_id);
        if ($creator) {
            $creator->update(
                [
                    'remaining_accounts_balance' => $transaction->remaining_accounts_balance,
                ]
            );
        }
        return back();
    }


    public function deleteResellerAccountTransaction($transaction): RedirectResponse
    {
        $transaction = ResellerClosingAccount::where('id', $transaction)->withoutGlobalScope('pending')->firstOrFail();
        $transaction->delete();
        return back();
    }
}
