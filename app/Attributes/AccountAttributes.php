<?php
	
	
	namespace App\Attributes;
	
	
	use App\Account;
	
	trait AccountAttributes
	{

//
		public function getCurrentAmount(Account $account)
		{
			$currentAmount = $account->debit_transaction()->sum('amount');
			foreach ($account->children()->get() as $child){
				$currentAmount += self::getCurrentAmount($child);
			}
			
			return $currentAmount;
		}
		
		public function getLabelAttribute()
		{
			return $this->locale_name;
		}
		
		public function getCurrentAmountAttribute()
		{
			return money_format("%i",$this->getCurrentAmount($this));
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