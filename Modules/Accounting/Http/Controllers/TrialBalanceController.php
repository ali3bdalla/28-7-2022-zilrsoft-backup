<?php

namespace Modules\Accounting\Http\Controllers;

use App\Account;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class TrialBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {


        $mainAccounts = Account::where('parent_id', 0)->get();


        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0;
        $accounts = [];
        foreach ($mainAccounts as $mainAccount) {
            $children = Account::whereIn('id', $mainAccount->returnNestedTreeIds($mainAccount))->where([[
                'id', '!=', $mainAccount->id
            ]])->withCount('children')->having('children_count', 0)->get(); //
            $mainAccountChildren = [];
            foreach ($children as $account) {

                if (!is_null($account->statistics)) {
                    $debitAmount = $account->statistics->debit_amount;
                    $creditAmount = $account->statistics->credit_amount;
                } else {
                    $debitAmount = $account->_getDebitTransactionsAmount();
                    $creditAmount = $account->_getCreditTransactionsAmount();
                }

                if ($debitAmount > 0 || $creditAmount > 0) {
                    if ($account->_isCredit()) {
                        $accountTotalAmount = $creditAmount - $debitAmount;
                        $accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0;
                        $accountDebitBalance = $accountTotalAmount > 0 ? 0 : $accountTotalAmount * -1;
                    } else {
                        $accountTotalAmount = $debitAmount - $creditAmount;
                        $accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * -1 : 0;
                        $accountDebitBalance = $accountTotalAmount < 0 ? 0 : $accountTotalAmount;
                    }

                    $account->credit_amount = $creditAmount;
                    $account->debit_amount = $debitAmount;
                    $account->total_amount = $accountTotalAmount;
                    $account->credit_balance = $accountCreditBalance;
                    $account->debit_balance = $accountDebitBalance;

                    $totalCreditAmount +=  $account->moneyFormatter($creditAmount);
                    $totalDebitAmount += $account->moneyFormatter($debitAmount);
                    $totalCreditBalance += $account->moneyFormatter($accountCreditBalance);
                    $totalDebitBalance += $account->moneyFormatter($accountDebitBalance);
                    $mainAccountChildren[] = $account;
                }
            }
            $mainAccount->mainAccountChildren = $mainAccountChildren;
            $accounts[] = $mainAccount;
        }
//



            $totalCreditAmount = round($totalCreditAmount);
            $totalDebitAmount = round($totalDebitAmount);
            $totalCreditBalance =round($totalCreditBalance);
            $totalDebitBalance = round($totalDebitBalance);
//        return $accounts;
//

//
//
//         return ;
        return view('accounting::trial_balance.index2', compact('accounts', 'totalCreditAmount', 'totalDebitAmount', 'totalCreditBalance', 'totalDebitBalance'));
//        return view('accounting::trial_balance.index', compact('accounts', 'totalCreditAmount', 'totalDebitAmount', 'totalCreditBalance', 'totalDebitBalance'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('accounting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('accounting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('accounting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
