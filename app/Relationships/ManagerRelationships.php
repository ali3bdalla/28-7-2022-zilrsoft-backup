<?php
	
	namespace App\Relationships;
	
	use App\Account;
	use App\Branch;
	use App\Category;
	use App\Department;
	use App\Filter;
	use App\FilterValues;
	use App\Gateway;
	use App\ManagerGateways;
	use App\Organization;
	use App\Role;
	use App\User;
	
	trait ManagerRelationships
	{
		
		public function organization()
		{
			return $this->belongsTo(Organization::class,
				'organization_id'
			);
		}
		
		public function user()
		{
			return $this->belongsTo(User::class,'user_id');
		}
		
		public function department()
		{
			// return 1;
			return $this->belongsTo(Department::class,'department_id');
		}
		
		public function branch()
		{
			// return 1;
			return $this->belongsTo(Branch::class,'branch_id');
		}
		
		public function categories()
		{
			return $this->hasMany(Category::class,'creator_id');
		}
		
		public function filters()
		{
			return $this->hasMany(Filter::class,'creator_id');
		}
		
		public function filters_values()
		{
			return $this->hasMany(FilterValues::class,'creator_id');
		}
		
		public function accounts()
		{
			return $this->hasMany(Account::class,'creator_id');
		}
		
		public function gateways()
		{
			return $this->belongsToMany(Account::class,'manager_gateways','manager_id','gateway_id')
				->withTimestamps();
		}
		
	}
