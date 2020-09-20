<?php
	
	namespace App\Attributes;
	
	use App\Models\Account;
	
	trait  ManagerAttributes
	{



		public function getLocaleNameAttribute()
		{
			
			// if (app()->isLocale('ar'))
			// 	return $this->name_ar;
			
			return $this->name;
		}
		
		public function canDo($option)
		{
			return $this->can($option) == true ? 1 : 0;
			
		}
		
	}
	
	