<?php
	
	
	namespace App\Attributes;
	
	
	use App\Account;
	use App\Transaction;
	
	trait AccountAttributes
	{

//
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
				$currentAmount = Transaction::where('debitable_type','App\Item')->sum('amount') - $currentAmount =
						Transaction::where('creditable_type','App\Item')->sum('amount');
			}elseif ($account->type == 'debit'){
				$currentAmount = $account->debit_transaction()->sum('amount') - $currentAmount =
						$account->credit_transaction()->sum('amount');
			}else{
				$currentAmount = $account->credit_transaction()->sum('amount') - $currentAmount =
						$account->debit_transaction()->sum('amount');
			}
			
			
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