<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\ResellerClosingAccount;
use Carbon\Carbon;
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

        // return $managerCloseAccountList;
        return view('accounting.reseller_daily.account_close_list', compact('managerCloseAccountList'));
    }


    public function createResellerClosingAccount(Request $request)
    {
        $loggedUser = $request->user();
        $tempResellerAccount = Account::where([
            ['slug', 'temp_reseller_account'],
            ['is_system_account', true],
        ])->first();
        $remainingAccountsBalanceAmount = $loggedUser->remaining_accounts_balance;
        $accountsClosedAt = $loggedUser->accounts_closed_at;

        if($accountsClosedAt != null)
        {
            $accountsClosedAt  = Carbon::parse($accountsClosedAt);
            $paymentQuery = Payment::whereDate('created_at','>',$accountsClosedAt)->where([
                ['creator_id',$loggedUser->id],
            ]);
            
        }else
        {
            $paymentQuery = Payment::where([
                ['creator_id',$loggedUser->id],
            ]);
        }
        $inAmount =  $paymentQuery->where('payment_type','receipt')->sum('amount');
        $outAmount =  0;
        $gateways = $loggedUser->gateways()->get();
        return view('accounting.reseller_daily.account_close', compact('inAmount', 'loggedUser', 'accountsClosedAt','outAmount','gateways','remainingAccountsBalanceAmount'));
    }
    //
}
