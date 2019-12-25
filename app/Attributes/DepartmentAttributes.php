<?php
	
	namespace App\Attributes;
	
	
	trait DepartmentAttributes
	{
		
		public function getLocaleTitleAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->ar_title;
			
			return $this->title;
			
		}
	}
	
	// comment create new identity