<?php


namespace App\Http\Controllers\App\API;


use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return array
     */
    public function trailBalance(Request $request)
    {

        $mainAccounts = Account::where('parent_id', 0)->get();

        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0;
        $accounts = [];
        foreach ($mainAccounts as $mainAccount) {

            if (
                $request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
                $request->filled('endDate')
            ) {
                $children = Account::whereIn('id', $mainAccount->getChildrenHashMap())->where(
                    [[
                        'id', '!=', $mainAccount->id,
                    ]]
                )->withTrashed()->get();//->withCount('children')->having('children_count', 0)\


            } else {
                $children = Account::whereIn('id', $mainAccount->getChildrenHashMap())->where(
                    [[
                        'id', '!=', $mainAccount->id,
                    ]]
                )->get();
            }
            $mainAccountChildren = [];
            foreach ($children as $account) {
                if (
                    $request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
                    $request->filled('endDate')
                ) {
                    $startDate = Carbon::parse($request->input("startDate"));
                    $endDate = Carbon::parse($request->input("endDate"));

                    $debitAmount = $account->snapshots()->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)->sum('debit_amount');
                    $creditAmount = $account->snapshots()->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)->sum('credit_amount');

                } else {
                    $debitAmount = $account->snapshots()->sum('debit_amount');
                    $creditAmount = $account->snapshots()->sum('credit_amount');
                }


                if ($debitAmount > 0 || $creditAmount > 0) {
                    if ($account->isCredit()) {
                        $accountTotalAmount = $creditAmount - $debitAmount;
                        $accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0;
                        $accountDebitBalance = $accountTotalAmount > 0 ? 0 : $accountTotalAmount * -1;
                    } else {
                        $accountTotalAmount = $debitAmount - $creditAmount;
                        $accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * -1 : 0;
                        $accountDebitBalance = $accountTotalAmount < 0 ? 0 : $accountTotalAmount;
                    }

                    $account->credit_amount = moneyFormatter($creditAmount);
                    $account->debit_amount = moneyFormatter($debitAmount);
                    $account->total_amount = moneyFormatter($accountTotalAmount);
                    $account->credit_balance = moneyFormatter($accountCreditBalance);
                    $account->debit_balance = moneyFormatter($accountDebitBalance);


                    if (moneyFormatter($accountDebitBalance) > 0 || moneyFormatter($accountCreditBalance) > 0) {
                        $totalCreditAmount = moneyFormatter($totalCreditAmount + ((float)$creditAmount));
                        $totalDebitAmount = moneyFormatter($totalDebitAmount + ((float)$debitAmount));
                        $totalCreditBalance = moneyFormatter($totalCreditBalance + ((float)$accountCreditBalance));
                        $totalDebitBalance = moneyFormatter($totalDebitBalance + ((float)$accountDebitBalance));
                        $mainAccountChildren[] = $account;
                    }
                }

            }
            $mainAccount->mainAccountChildren = $mainAccountChildren;
            $accounts[] = $mainAccount;
        }


        return [
            'accounts' => $accounts,
            'totalCreditAmount' => $totalCreditAmount,
            'totalDebitAmount' => $totalDebitAmount,
            'totalCreditBalance' => $totalCreditBalance,
            'totalDebitBalance' => $totalDebitBalance,
        ];
//			return view('accounting_module.financial_statements.trial_balance', compact('accounts', 'totalCreditAmount', 'totalDebitAmount', 'totalCreditBalance', 'totalDebitBalance'));
    }
}
