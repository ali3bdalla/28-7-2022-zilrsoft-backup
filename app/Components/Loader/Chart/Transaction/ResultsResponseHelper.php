<?php
	
	
	namespace App\Components\Loader\Chart\Transaction;
	
	
	trait ResultsResponseHelper
	{
		
		private function globalTransactionsResults()
		{
			
			if (!$this->isNotFirstPage()){
				$this->accountChildrenAccounts();
			}


//			dd($this->db_result_rows,$this->db_rows);
			foreach ($this->db_rows as $transaction){
				if ($transaction->debitable_id == $this->account->id){
					$this->total_debit_amount += $transaction['amount'];
					$transaction['type'] = 'debit';
					$transaction['credit_amount'] = 0;
					$transaction['debit_amount'] = $transaction['amount'];
				}else{
					$this->total_credit_amount += $transaction['amount'];
					$transaction['type'] = 'credit';
					$transaction['debit_amount'] = 0;
					$transaction['credit_amount'] = $transaction['amount'];
				}
				
				
				if ($this->is_credit_account){
					$amount = $this->total_credit_amount - $this->total_debit_amount;
					$transaction['total_credit_amount'] = $amount >= 0 ? $amount : 0;
					$transaction['total_debit_amount'] = $amount < 0 ? abs($amount) : 0;
				}else{
					$amount = $this->total_debit_amount - $this->total_credit_amount;
					$transaction['total_debit_amount'] = $amount > 0 ? $amount : 0;
					$transaction['total_credit_amount'] = $amount < 0 ? abs($amount) : 0;
				}
				
				
				$transaction['is_transaction'] = true;
				
				$this->db_result_rows[] = $transaction;
				
			}
			
		}
		
		private function accountChildrenAccounts()
		{
			$results = [];
			foreach ($this->account->children as $child){
				$current_amount = $child->current_amount;
				
				if ($child->type == 'credit'){
					$child['credit_amount'] = $current_amount >= 0 ? $current_amount : 0;
					$child['debit_amount'] = $current_amount < 0 ? abs($current_amount) : 0;
				}else{
					$child['debit_amount'] = $current_amount >= 0 ? $current_amount : 0;
					$child['credit_amount'] = $current_amount < 0 ? abs($current_amount) : 0;
					
				}
				
				$this->total_credit_amount += floatval($child['credit_amount']);
				$this->total_debit_amount += floatval($child['debit_amount']);
				
				if ($child->type == 'credit'){
					$amount = $this->total_credit_amount - $this->total_debit_amount;
					$child['total_credit_amount'] = $amount >= 0 ? $amount : 0;
					$child['total_debit_amount'] = $amount < 0 ? abs($amount) : 0;
				}else{
					$amount = $this->total_debit_amount - $this->total_credit_amount;
					$child['total_debit_amount'] = $amount > 0 ? $amount : 0;
					$child['total_credit_amount'] = $amount < 0 ? abs($amount) : 0;
				}
				$child['is_transaction'] = false;
				$results[] = $child;
				
			}
			
			if (!$this->isNotFirstPage()){
				$this->db_result_rows = $results;
			}
		}
	}