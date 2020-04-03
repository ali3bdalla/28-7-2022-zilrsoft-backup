<?php
	
	
	namespace App\Components\Observer\Transactions;
	
	
	use App\Account;
	use App\Item;
	use App\Transaction;
	use Illuminate\Support\Facades\DB;
	
	class Created
	{
		/**
		 * @var Transaction
		 */
		private $transaction;
		
		public function __construct(Transaction $transaction)
		{
			$this->transaction = $transaction;
			$this->prepareAndUpdateData();
			
		}
		
		private function prepareAndUpdateData()
		{
			
			$stock_account = Account::toGetAccountWithSlug('stock');
			
			if ($this->transaction->debitable instanceof Account && $this->transaction->debitable != $stock_account){
				
				$stats = $this->transaction->debitable->statistics;
				if (!$stats)
					$stats = $this->transaction->debitable->statistics()->create();
				$stats->update([
					'transactions_count' => DB::raw("transactions_count + 1"),
					'total_amount' => $this->getAccountCurrentAmount($this->transaction->debitable),
				]);
				
			}
			
			
			if ($this->transaction->creditable instanceof Account && $this->transaction->creditable != $stock_account){
				$stats = $this->transaction->creditable->statistics;
				if (!$stats)
					$stats = $this->transaction->creditable->statistics()->create();
				$stats->update([
					'transactions_count' => DB::raw("transactions_count + 1"),
					'total_amount' => $this->getAccountCurrentAmount($this->transaction->creditable),
				]);
			}
			
			
			if ($this->transaction->debitable instanceof Item || $this->transaction->creditable instanceof Item){
				$stats = $stock_account->statistics;
				if (!$stats)
					$stats = $stock_account->statistics()->create();
				
				$stats->update([
					'transactions_count' => DB::raw("transactions_count + 1"),
					'total_amount' => $this->getStockCurrentAmount(),
				]);
			}

			
		}
		
		private function getAccountCurrentAmount(Account $account)
		{
			if ($account->type == 'credit')
				return $account->credit_transaction()->sum('amount') -
					$account->debit_transaction()->sum('amount');
			
			return $account->debit_transaction()->sum('amount') -
				$account->credit_transaction()->sum('amount');
		}
		
		private function getStockCurrentAmount()
		{
			return Transaction::where('debitable_type','App\Item')->sum('amount') -
				Transaction::where('creditable_type','App\Item')->sum('amount');
		}
		
	}