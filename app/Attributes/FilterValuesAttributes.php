<?php
	
	namespace App\Attributes;
	
	trait FilterValuesAttributes
	{
		
		public function getLocaleNameAttribute()
		{
			
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			return $this->name;
		}
		
		public function setAsLastUsedValue()
		{
			$this->update([
				'updated_at' => now()
			]);
			# code...
		}
	}
	