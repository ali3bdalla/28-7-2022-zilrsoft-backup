<?php
	
	
	namespace App\Components\Loader\Chart\Transaction;
	
	
	use App\Transaction;
	
	trait TransactionsHelper
	{
		
		
		private function loadGlobalTransactions()
		{
			$this->db_rows = Transaction::where([
				['creditable_type',get_class($this->account)],
				['creditable_id',$this->account->id]
			])->
			OrWhere([
				['debitable_type',get_class($this->account)],
				['debitable_id',$this->account->id]
			])->orderBy('id','asc')->paginate($this->getPerPage());
			
			

			
		}
		
		private function loadStockTransactions()
		{
		
		}
		
		private function loadClientsTransactions()
		{
		
		}
		
		private function loadVendorsTransactions()
		{
		
		}
	}