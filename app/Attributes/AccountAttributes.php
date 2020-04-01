<?php
	
	
	namespace App\Attributes;
	
	
	use App\Account;
	use App\Transaction;
	
	trait AccountAttributes
	{

//
		public static function toGetAccountWithSlug($slug,$is_system_account = true)
		{
			return Account::where([['slug',$slug],['is_system_account',$is_system_account]])->first();
		}
		
		public function getLabelAttribute()
		{
			return $this->locale_name;
		}
		
		public function getCurrentAmountAttribute()
		{
			return money_format("%i",$this->getCurrentAmount($this));
		}
		
		public function getCurrentAmount(Account $account)
		{
			
			if ($account->slug == 'stock'){
				$currentAmount = Transaction::where('debitable_type','App\Item')->sum('amount') -
					Transaction::where('creditable_type','App\Item')->sum('amount');
			}elseif ($account->type == 'debit'){
				$currentAmount = $account->debit_transaction()->sum('amount') -
					$account->credit_transaction()->sum('amount');
			}else{
				$currentAmount = $account->credit_transaction()->sum('amount') -
					$account->debit_transaction()->sum('amount');
			}
//
////
////			if ($account->slug == 'stock'){
////				$currentAmount = Transaction::where('debitable_type','App\Item')->sum('amount') -
////					Transaction::where('creditable_type','App\Item')->sum('amount');
////			}else
//				if ($account->type == 'debit'){
//				$currentAmount = $account->statistics->debit_transactions_total_amount -
//					$account->statistics->credit_transactions_total_amount;
////				$currentAmount = $account->debit_transaction()->sum('amount') -
////					$account->credit_transaction()->sum('amount');
//
//			}else{
//				$currentAmount = $account->statistics->credit_transactions_total_amount -
//					$account->statistics->debit_transactions_total_amount;
////				$currentAmount = $account->credit_transaction()->sum('amount') -
////					$account->debit_transaction()->sum('amount');
//			}

//
			foreach ($account->children()->get() as $child){
				$currentAmount += self::getCurrentAmount($child);
			}
			
			
			return $currentAmount;
		}
		
		public function getTitleAttribute()
		{
			return $this->ar_name;
		}
		
		public function getLocaleNameAttribute()
		{
			
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			return $this->name;
		}
		
	}