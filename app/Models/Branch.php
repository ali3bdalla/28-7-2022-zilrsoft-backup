<?php
	
	namespace App\Models;
	
	
	class Branch extends BaseModel
	{
		//
		
		
		protected $guarded = [];
		
		protected $appends = [
			'locale_name'
		];
		
		
		public function getLocaleNameAttribute()
		{
			if(app()->isLocale('ar'))
				return $this->ar_name;
			
			return $this->name;
		}
		
		
		public function departments()
		{
			return $this->hasMany(Department::class, 'branch_id');
		}
		
	}
