<?php
	
	
	namespace App\Http\Controllers\Api;
	
	
	use App\Http\Controllers\Controller;
	use App\Models\Account;
	use Carbon\Carbon;
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\View\View;
	
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
			
			foreach($mainAccounts as $mainAccount) {
				
				$children = Account::whereIn('id', $mainAccount->getChildrenHashMap())->where(
					[[
						'id', '!=', $mainAccount->id,
					]]
				)->get();//->withCount('children')->having('children_count', 0)
				$mainAccountChildren = [];
				foreach($children as $account) {
					if(
						$request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
						$request->filled('endDate')
					) {
						$startDate = Carbon::parse($request->input("startDate"));
						$endDate = Carbon::parse($request->input("endDate"));
						
						$debitAmount = $account->snapshots()->whereBetween('created_at', [$startDate, $endDate])->sum('debit_amount');
						$creditAmount = $account->snapshots()->whereBetween('created_at', [$startDate, $endDate])->sum('credit_amount');
						
					} else {
						$debitAmount = $account->total_debit_amount;
						$creditAmount = $account->total_credit_amount;
					}
					
					
					if($debitAmount > 0 || $creditAmount > 0) {
						if($account->_isCredit()) {
							$accountTotalAmount = $creditAmount - $debitAmount;
							$accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0;
							$accountDebitBalance = $accountTotalAmount > 0 ? 0 : $accountTotalAmount * - 1;
						} else {
							$accountTotalAmount = $debitAmount - $creditAmount;
							$accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * - 1 : 0;
							$accountDebitBalance = $accountTotalAmount < 0 ? 0 : $accountTotalAmount;
						}
						
						$account->credit_amount = displayMoney($creditAmount);
						$account->debit_amount = displayMoney($debitAmount);
						$account->total_amount = displayMoney($accountTotalAmount);
						$account->credit_balance = displayMoney($accountCreditBalance);
						$account->debit_balance = displayMoney($accountDebitBalance);
						
						$totalCreditAmount = displayMoney($totalCreditAmount + ((float)$creditAmount));
						$totalDebitAmount = displayMoney($totalDebitAmount + ((float)$debitAmount));
						$totalCreditBalance = displayMoney($totalCreditBalance + ((float)$accountCreditBalance));
						$totalDebitBalance = displayMoney($totalDebitBalance + ((float)$accountDebitBalance));
						$mainAccountChildren[] = $account;
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