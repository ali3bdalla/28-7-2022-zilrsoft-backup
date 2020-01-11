<?php
	
	namespace App\Relationships;
	
	use App\Account;
	use App\Branch;
	use App\Category;
	use App\Department;
	use App\Filter;
	use App\FilterValues;
	use App\Organization;
	use App\User;
	use Carbon\Carbon;
	
	//	use App\Gateway;
	//	use App\Role;
	
	trait ManagerRelationships
	{
		
		public function dailyTransactionsAmount($period = null)
		{
			
			
			$dailyAccount = Account::where([
				['slug','temp_reseller_account'],
				['is_system_account',true],
			])->first();
			if ($period == null){
				return $dailyAccount->debit_transaction()->where('creator_id',$this->id)->whereDate('created_at',
						Carbon::today())->sum('amount') -
					$dailyAccount->credit_transaction()->where('creator_id',$this->id)->whereDate('created_at',Carbon::today())->sum('amount');
			}
			
			
			return $dailyAccount->debit_transaction()->where('creator_id',$this->id)->whereBetween('created_at',[
					Carbon::parse($period),
					Carbon::now()
				])->sum('amount') -
				$dailyAccount->credit_transaction()->where('creator_id',$this->id)->whereBetween('created_at',[
					Carbon::parse($period),
					Carbon::now()
				])->sum('amount');
			
		}
		
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
//			->withPivot('created_at as relationship_created_at')
			return
				$this->belongsToMany(Account::class,
					'manager_gateways',
					'manager_id',
					'gateway_id')
					->withPivot('order_number as order_number')
					->orderBy('order_number','asc')
					->withTimestamps();
		}
		
	}
