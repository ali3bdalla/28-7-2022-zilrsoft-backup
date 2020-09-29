<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ResellerClosingAccount;
use Illuminate\Http\Request;

class DailyController extends Controller
{

    public function resellerClosingAccountsIndex()
    {
        $managerCloseAccountList = ResellerClosingAccount::where([
            ['creator_id', auth()->user()->id],
            ['transaction_type', "close_account"],

        ])->orWhere([
            ['receiver_id', auth()->user()->id]
        ])->orderBy('id', 'desc')->paginate(15);

        return view('accounting.reseller_daily.account_close_list', compact('managerCloseAccountList'));
    }


    public function createResellerClosingAccount(Request $request)
    {
        $loggedUser = $request->user();

        $tempResellerAccount = Account::where([
            ['slug', 'temp_reseller_account'],
            ['is_system_account', true],
        ])->first();

        $lastRemainingTransferAmount = 0;
        $lastAccountCloseTransaction = $loggedUser->resellerClosingAccount()->orderBy('id', 'desc')->first();
        if (!empty($lastAccountCloseTransaction) && $lastAccountCloseTransaction->transaction_type == "transfer") {
            $lastDebit = $tempResellerAccount->transactions()->where([
                ['container_id', $lastAccountCloseTransaction->container_id]
            ])->first();
            if (!empty($lastDebit)) {
                $lastRemainingTransferAmount = $lastDebit->amount;
            }
        }


        $periodSalesAmount = 0;// $request->user()->dailyTransactionsAmount();
        $gateways = $loggedUser->gateways()->get();
        return view('accounting.reseller_daily.account_close', compact('periodSalesAmount', 'gateways', 'lastRemainingTransferAmount'));
    }
    //
}
