<?php

namespace Modules\Accounting\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TrialBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $accountsDB = Account::withCount('children')->having('children_count', '=', 0)->get();
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0;

        $diff = [];
        $accounts = [];
        foreach($accountsDB as $account)
        {
            if(!is_null($account->statistics))
            {
                $debitAmount = $account->statistics->debit_amount;
                $creditAmount = $account->statistics->credit_amount;



            }else
            {
                $debitAmount = $account->_getDebitTransactionsAmount();
                $creditAmount = $account->_getCreditTransactionsAmount();
                $diff[] = [
                    'debit'=>$debitAmount,
                    'credit'=>$creditAmount,
                ];
            }



            if($debitAmount!=0 || $creditAmount != 0)
            {
                if($account->_isCredit())
                {
                    $accountTotalAmount = $creditAmount - $debitAmount;
                    $accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount :0 ;
                    $accountDebitBalance = $accountTotalAmount > 0 ? 0 : $accountTotalAmount * -1 ;
                }else
                {
                    $accountTotalAmount = $debitAmount - $creditAmount;
                    $accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * -1  : 0;
                    $accountDebitBalance = $accountTotalAmount < 0 ? 0 : $accountTotalAmount ;
                }






                $account->credit_amount = $creditAmount;
                $account->debit_amount = $debitAmount;
                $account->total_amount = $accountTotalAmount;
                $account->credit_balance = $accountCreditBalance;
                $account->debit_balance = $accountDebitBalance;

                $totalCreditAmount+=$creditAmount;
                $totalDebitAmount+=$debitAmount;
                $totalCreditBalance+=$accountCreditBalance;
                $totalDebitBalance+=$accountDebitBalance;
                $accounts[] = $account;
            }




        }


        return view('accounting::trial_balance.index',compact('accounts','totalCreditAmount','totalDebitAmount','totalCreditBalance','totalDebitBalance'));
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
