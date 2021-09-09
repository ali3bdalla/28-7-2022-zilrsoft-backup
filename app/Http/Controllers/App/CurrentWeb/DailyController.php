<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\Entity\ActivateEntityJob;
use App\Models\Manager;
use App\Models\ResellerClosingAccount;
use App\Repository\AccountsDailyRepositoryContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{

    private AccountsDailyRepositoryContract $accountsDailyRepositoryContract;

    public function __construct(AccountsDailyRepositoryContract $accountsDailyRepositoryContract)
    {
        $this->accountsDailyRepositoryContract = $accountsDailyRepositoryContract;
    }

    public function resellerClosingAccountsIndex()
    {
//        $this->accountsDailyRepositoryContract->createDailyCloseAccountAggregate([
//           [
//               'id' => 38,
//               'amount' => 1800
//           ]
//        ]);
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
                ['creator_id', auth()->user()->id],
                ['transaction_type', "transfer"],
            ]
        )->orWhere(
            [
                ['receiver_id', auth()->user()->id],
            ]
        )->orderBy('id', 'desc')->paginate(15);

        return view('accounting.reseller_daily.tranfers_list', compact('managerCloseAccountList'));
    }

    public function createResellerAccountTransaction(Request $request)
    {
        $manager_gateways = $request->user()->gateways()->get();
        $managers = Manager::where('id', '!=', $request->user()->id)->with('gateways')->get();
        $gateways = [];
        foreach ($managers as $manager) {
            foreach ($manager->gateways as $gateway) {
                if ($gateway->is_gateway) {
                    $gateway['receiver_id'] = $manager['id'];
                    $gateways[] = $gateway;

                }

            }
        }


        return view('accounting.reseller_daily.transfer_amounts', compact('gateways', 'manager_gateways'));
    }

    public function confirmResellerAccountTransaction($transaction): RedirectResponse
    {
        $transaction = ResellerClosingAccount::where("id", $transaction)->withoutGlobalScope('pending')->firstOrFail();
        if ($transaction->receiver_id == auth()->user()->id && $transaction->transaction_type == 'transfer') {
            $container = $transaction->container;
            dispatch_now(new ActivateEntityJob($container));
            $transaction->update(
                [
                    'is_pending' => false,
                ]
            );

            $creator = Manager::find($transaction->creator_id);
            if ($creator) {
                $creator->update(
                    [
                        'remaining_accounts_balance' => $transaction->remaining_accounts_balance,
                    ]
                );
            }

        }

//        return back();

        return back();
    }


    public function deleteResellerAccountTransaction($transaction)
    {
        $transaction = ResellerClosingAccount::where('id', $transaction)->withoutGlobalScope('pending')->firstOrFail();
        $transaction->delete();
        return back();
    }
}
