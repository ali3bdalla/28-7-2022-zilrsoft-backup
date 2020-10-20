<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Account;
	use Illuminate\Http\Request;
	
	class FinancialStatementController extends Controller
	{
		
		public function index()
		{
			return view('accounting_module.financial_statements.index');
		}
		
		/**
		 * Display a listing of the resource.
		 * @param Request $request
		 * @return Application|Factory|View
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
					
					$debitAmount = $account->total_debit_amount;
					$creditAmount = $account->total_credit_amount;
					
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
						$totalCreditBalance =displayMoney( $totalCreditBalance + ((float)$accountCreditBalance));
						$totalDebitBalance = displayMoney($totalDebitBalance + ((float)$accountDebitBalance));
						$mainAccountChildren[] = $account;
					}
				}
				$mainAccount->mainAccountChildren = $mainAccountChildren;
				$accounts[] = $mainAccount;
			}
			
			
			return view('accounting_module.financial_statements.trail_balance', compact('accounts', 'totalCreditAmount', 'totalDebitAmount', 'totalCreditBalance', 'totalDebitBalance'));
		}
		
	}
