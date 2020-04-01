<?php
	
	
	namespace App\Components\Observer\Transactions;
	
	
	use App\Transaction;
	
	class TransactionsObserver
	{
		public function created(Transaction $transaction)
		{
			new Created($transaction);
		}
	}