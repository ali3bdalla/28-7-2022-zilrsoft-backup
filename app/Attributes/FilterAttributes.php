<?php
	
	namespace App\Attributes;
	
	trait FilterAttributes
	{
		
		
		public function getLocaleNameAttribute()
		{
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			
			return $this->name;
		}
	}
	
