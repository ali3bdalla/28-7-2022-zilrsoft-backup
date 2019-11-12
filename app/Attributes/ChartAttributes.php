<?php
	
	
	namespace App\Attributes;
	
	
	use App\AccountingChartAccounts;
	
	trait ChartAttributes
	{
	
//
		public function getTotalAttribute()
		{
			return money_format("%i",rand(1,100) * 100);
		}
		public function scopeMainOnly($query)
		{
			return $query->where('parent_id',0);
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