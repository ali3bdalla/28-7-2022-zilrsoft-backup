<?php
	
	namespace App\Attributes;
	
	trait BranchAttributes
	{
		
		public function getLocaleNameAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->ar_name;
			
			return $this->name;
		}
		
	}
