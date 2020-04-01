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
		private $debit_account = null;
		private $credit_account = null;
		private $debit_account_stats = null;
		private $credit_account_stats = null;
		
		public function __construct(Transaction $transaction)
		{
			$this->transaction = $transaction;
			$this->prepareAndUpdateData();
			
		}
		
		private function prepareAndUpdateData()
		{
			
			$stock_account = Account::toGetAccountWithSlug('stock');
			
			if ($this->transaction->debitable instanceof Account && $this->transaction->debitable != $stock_account){
				$this->debit_account = $this->transaction->debitable;
				$this->debit_account_stats = $this->debit_account->statistics;
				if (!$this->debit_account_stats)
					$this->debit_account_stats = $this->debit_account->statistics()->create();
				$this->debit_account_stats->update([
					'debit_transactions_count' => DB::raw("debit_transactions_count + 1"),
					'debit_transactions_total_amount' => DB::raw("debit_transactions_total_amount + {$this->transaction->amount}"),
				]);
				
			}
			
			
			if ($this->transaction->creditable instanceof Account && $this->transaction->creditable != $stock_account){
				$this->credit_account = $this->transaction->creditable;
				$this->credit_account_stats = $this->credit_account->statistics;
				if (!$this->credit_account_stats)
					$this->credit_account_stats = $this->credit_account->statistics()->create();
				
				$this->credit_account_stats->update([
					'credit_transactions_count' => DB::raw("credit_transactions_count + 1"),
					'credit_transactions_total_amount' => DB::raw("credit_transactions_total_amount + {$this->transaction->amount}"),
				]);
			}


//			// stock work
			if ($this->transaction->debitable instanceof Item){
				$this->debit_account = $stock_account;
				$this->debit_account_stats = $this->debit_account->statistics;
				if (!$this->debit_account_stats)
					$this->debit_account_stats = $this->debit_account->statistics()->create();
				$this->debit_account_stats->update([
					'debit_transactions_count' => DB::raw("debit_transactions_count + 1"),
					'debit_transactions_total_amount' => DB::raw("debit_transactions_total_amount + {$this->transaction->amount}"),
				]);
			}elseif ($this->transaction->creditable instanceof Item){
				$this->credit_account =$stock_account;
				$this->credit_account_stats = $this->credit_account->statistics;
				if (!$this->credit_account_stats)
					$this->credit_account_stats = $this->credit_account->statistics()->create();

				$this->credit_account_stats->update([
					'credit_transactions_count' => DB::raw("credit_transactions_count + 1"),
					'credit_transactions_total_amount' => DB::raw("credit_transactions_total_amount + {$this->transaction->amount}"),
				]);
			}
			
			
		}
		
	}