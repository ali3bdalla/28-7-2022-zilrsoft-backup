<?php
	
	
	namespace App\Accounting;
	
	
	use App\Item;
	use App\Transaction;
	use App\User;
	
	trait ChartAccounting
	{
		public function get_users_transactions($type)
		{
			
			if ($type == 'vendor_balance'){
				$users_source = User::where('is_vendor',true)->get();
			}else{
				$users_source = User::where('is_client',true)->get();
			}
			
			
			$total_credit = 0;
			$total_debit = 0;
			$users = [];
			foreach ($users_source as $user){
				
				$amounts = $this->get_single_user_transactions($user['id'],$type);
				
				
				$user['total_debit'] = $amounts['total_debit'];
				$user['total_credit'] = $amounts['total_credit'];
				
				
				$total_credit = $user['total_credit'] + $total_credit;
				$total_debit = $user['total_debit'] + $total_debit;
				
				
				$balance = $total_debit - $total_credit; //  current transaction
//
				if ($balance < 0){
					
					
					$user['balance_debit'] = 0;
					$user['balance_credit'] = abs($balance);
					
					
				}else{
					$user['balance_credit'] = 0;
					$user['balance_debit'] = abs($balance);
					
					
				}
				
				
				$users[] = $user;
			}
			
			return $users;
		}
		
		public function get_single_user_transactions($user_id,$description)
		{
			
			$total_credit = 0;
			$total_debit = 0;
			$transactions = Transaction::where([
				['user_id',$user_id],
				['description',$description],
			])->get();
			
			foreach ($transactions as $transaction){
				if ($transaction['creditable'] == ""){
					$total_debit = $transaction['amount'] + $total_debit;
				}else{
					$total_credit = $transaction['amount'] + $total_credit;
				}
			}
			
			return [
				'total_credit' => $total_credit,
				'total_debit' => $total_debit,
			];
		}
		
		public function get_account_transactions_types_amount($account)
		{
			$total_credit = 0;
			$total_debit = 0;
			
			
			if ($account->slug == 'stock'){
				return $this->get_account_stock_item_transactions();
			}
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
		
		public function get_account_stock_item_transactions()
		{
//			$source_items = Item::all();
			$source_items = Item::with('debit_transaction')->paginate(1000)->sortByDesc(function ($query){
				return $query->debit_transaction->sum('amount');
			});
			$items = [];
			$total_credit = 0;
			$total_debit = 0;
			foreach ($source_items as $item){
				$item['transactions'] = $this->get_all_account_transaction($item);
				$item['total_debit'] = $item['transactions']['total_debit'];
				$item['total_credit'] = $item['transactions']['total_credit'];
				$total_credit = $item['total_credit'] + $total_credit;
				$total_debit = $item['total_debit'] + $total_debit;
				$balance = $total_debit - $total_credit;
				if ($balance < 0){
					
					
					$item['balance_debit'] = 0;
					$item['balance_credit'] = abs($balance);
					
					
				}else{
					$item['balance_credit'] = 0;
					$item['balance_debit'] = abs($balance);
					
					
				}
				$items[] = $item;
			}
			
			
			return [
				'total_credit' => $total_credit,
				'total_debit' => $total_debit,
				'items' => $items
			];
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

//			dd(collect($transactions)->sortKeys());
			return [
				'transaction' => collect($transactions)->sortBy('created_at'),
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
		
		public function load_item_transactions($item)
		{
			
			return $this->get_all_account_transaction($item)['transaction'];
//
//			$item['total_debit'] = $item['transactions']['total_debit'];
//			$item['total_credit'] = $item['transactions']['total_credit'];
//
//
//			$total_credit = $item['total_credit'] + $total_credit;
//			$total_debit = $item['total_debit'] + $total_debit;
//
//
//////
////
////
////
//			$balance = $total_debit - $total_credit; //  current transaction
////
//			if ($balance < 0){
//
//
//				$item['balance_debit'] = 0;
//				$item['balance_credit'] = abs($balance);
//
//
//			}else{
//				$item['balance_credit'] = 0;
//				$item['balance_debit'] = abs($balance);
//
//
//			}
//
//


//			return $this->get_all_account_transaction($account,$client_id,'client_balance');
		}
	}