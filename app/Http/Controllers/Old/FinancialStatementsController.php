<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Controllers\ControllersHelper\AccountGeneralHelper;
	
	class FinancialStatementsController extends Controller
	{
		use AccountGeneralHelper;
		
		public function index()
		{
			return view('financial_statements.index');
		}
		
		public function trail_balance()
		{
			
			$source_accounts = Account::all();
			
			
			$accounts = [];
			foreach ($source_accounts as $account){
				$amounts = $this->get_account_transactions_types_amount($account);
				$row['id'] = $account->id;
				$row['locale_name'] = $account->locale_name;
				
				$row['total_debit'] = $amounts['total_debit'];
				$row['total_credit'] = $amounts['total_credit'];
				
				
				if ($account['type'] == 'credit'){
					$balance = $amounts['total_credit'] - $amounts['total_debit'];
					
					if ($balance < 0){
						
						$row['balance_credit'] = 0;
						$row['balance_debit'] = abs($balance);
					}else{
						$row['balance_debit'] = 0;
						$row['balance_credit'] = abs($balance);
						
						
					}
					
					
				}else{
					$balance = $amounts['total_debit'] - $amounts['total_credit'];
					
					if ($balance < 0){
						$row['balance_debit'] = 0;
						$row['balance_credit'] = abs($balance);
					}else{
						$row['balance_credit'] = 0;
						$row['balance_debit'] = abs($balance);
						
						
					}
				}
				
				$accounts[] = $row;
				
			}
//			return $accounts;
			
			return view('financial_statements.trail_balance',compact('accounts'));
			
		}
		
	}
