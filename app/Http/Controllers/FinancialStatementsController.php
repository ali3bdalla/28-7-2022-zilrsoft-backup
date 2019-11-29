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
				$accounts[] = $row;
				
			}
			
			return view('financial_statements.trail_balance',compact('accounts'));
			
		}
		
	}
