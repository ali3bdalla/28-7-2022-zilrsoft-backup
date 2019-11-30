<?php
	
	
	namespace App\Http\Controllers\ControllersHelper;
	
	
	use App\Transaction;
	
	trait AccountGeneralHelper
	{
		
		public function get_account_transactions_types_amount($account)
		{
			$total_credit = 0;
			$total_debit = 0;
			
			$children = $this->loop_in_main_children($account);
			
			$response = $this->get_all_account_transaction($account);
			foreach ($children as $child){
				$total_credit = $total_credit + $child['credit'];
				$total_debit = $total_debit + $child['debit'];
			}
			
			$total_credit = $total_credit + $response['total_credit'];
			$total_debit = $total_debit + $response['total_debit'];
			
			return [
				'total_debit' => $total_debit,
				'total_credit' => $total_credit,
			];
			
		}
		
		public function loop_in_main_children($account)
		{
			$data = [];
			
			foreach ($account->children as $child){
				
				$child['is_transaction'] = false;
				$single_main_child = $child;
				$calc = $this->get_all_child_data($child);
				$single_main_child['credit'] = $calc['credit'];
				$single_main_child['debit'] = $calc['debit'];
				$data[] = $single_main_child;
				
			}
			
			return $data;
		}
		
		public function get_all_child_data($child)
		{
			
			$response = $this->get_all_account_transaction($child);
			
			$credit = $response['total_credit'];
			$debit = $response['total_debit'];
			
			foreach ($child->children as $child){
				$sub_child_request = $this->get_all_child_data($child);
				$credit = $credit + $sub_child_request['credit'];
				$debit = $debit + $sub_child_request['debit'];
			}
			
			return ['credit' => $credit,'debit' => $debit];
		}
		
		public function get_all_account_transaction($account,$user_id = 0,$description = "")
		{
			if ($user_id != 0){
				$debit_transactions = $account->debit_transaction()->where([
					['user_id',$user_id],
					['description',$description]
				])->get();
				$credit_transactions = $account->credit_transaction()->where([
					['user_id',$user_id],
					['description',$description]
				])->get();
			}else{
				$debit_transactions = $account->debit_transaction;
				$credit_transactions = $account->credit_transaction;
			}
//			dd();
			
			
			$total_credit = 0;
			$total_debit = 0;
			
			$transactions = [];
			foreach ($debit_transactions as $transaction){
				
				$total_debit = $total_debit + $transaction['amount'];
				$transaction['type'] = 'debit';
				$transaction['is_transaction'] = true;
				$transactions[] = $transaction;
			}
			
			
			foreach ($credit_transactions as $transaction){
				
				$total_credit = $total_credit + $transaction['amount'];
				$transaction['type'] = 'credit';
				$transaction['is_transaction'] = true;
				$transactions[] = $transaction;
			}
			
			
			return [
				'transaction' => $transactions,
				'total_debit' => $total_debit,
				'total_credit' => $total_credit,
			];
			
		}
		
		public function load_account_transactions($account)
		{
			
			
			$data = $this->get_all_account_transaction($account);
			$transactions = $data['transaction'];
			$children = $this->loop_in_main_children($account);
			
			foreach ($children as $child){
				$transactions[] = $child;
			}
			return $transactions;
			
		}
		
		public function load_vendor_transactions($account,$vendor_id)
		{
			
			return Transaction::where(
				[
					['creditable_id',$account->id],
					['creditable_type',get_class($account)],
					['user_id',$vendor_id],
					['description','vendor_balance']
				]
			)->orWhere(
				[
					['debitable_id',$account->id],
					['debitable_type',get_class($account)],
					['user_id',$vendor_id],
					['description','vendor_balance']
				]
			)->get();
			
			
			return $this->get_all_account_transaction($account,$client_id,'client_balance');
		}
		
		public function load_client_transactions($account,$client_id)
		{
			
			return Transaction::where(
				[
					['creditable_id',$account->id],
					['creditable_type',get_class($account)],
					['user_id',$client_id],
					['description','client_balance']
				]
			)->orWhere(
				[
					['debitable_id',$account->id],
					['debitable_type',get_class($account)],
					['user_id',$client_id],
					['description','client_balance']
				]
			)->get();


//			return $this->get_all_account_transaction($account,$client_id,'client_balance');
		}
	}