<?php
	
	namespace App\Attributes;
	
	
	trait DepartmentAttributes
	{
		
		public function getLocaleTitleAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->title;
			
			return $this->ar_title;
			
		}
	}