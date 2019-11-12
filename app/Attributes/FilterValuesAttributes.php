<?php
	
	namespace App\Attributes;
	
	trait FilterValuesAttributes
	{
		
		use FilterValuesLocale;
		
		public function setAsLastUsedValue()
		{
			$this->update([
				'updated_at' => now()
			]);
			# code...
		}
	}
	
	trait  FilterValuesLocale
	{
		
		public function getLocaleNameAttribute()
		{
			
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			return $this->name;
		}
		
	}
