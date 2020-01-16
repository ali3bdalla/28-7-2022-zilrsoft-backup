<?php
	
	namespace App\Relationships;
	
	use App\Account;
	use App\Branch;
	use App\Category;
	use App\Department;
	use App\Filter;
	use App\FilterValues;
	use App\ManagerPrivateTransactions;
	use App\Organization;
	use App\User;
	use Carbon\Carbon;
	
	//	use App\Gateway;
	//	use App\Role;
	
	trait ManagerRelationships
	{
		
		/**
		 * @return mixed
		 */
		public function private_transactoins()
		{
			return $this->hasMany(ManagerPrivateTransactions::class,'creator_id');
		}
		
		public function dailyTransactionsAmount($period)
		{
			
//			dd(Carbon::now()->subDays(10));
			$startAt = Carbon::parse($period)->toDateTimeString();
			$dailyAccount = Account::where([
				['slug','temp_reseller_account'],
				['is_system_account',true],
			])->first();
			return $dailyAccount->debit_transaction()->where('creator_id',$this->id)->whereBetween('created_at',[
					Carbon::now()->subDays(10),
					Carbon::now()
				])->sum('amount') -
				$dailyAccount->credit_transaction()->where('creator_id',$this->id)->whereBetween('created_at',[
					Carbon::now()->subDays(10),
					Carbon::now()
				])->sum('amount');


//if ($period == null){
			//->whereDate('created_at',
			//						Carbon::today())
			//->whereDate('created_at',Carbon::today())
//				return $dailyAccount->debit_transaction()->where('creator_id',$this->id)->sum('amount') -
//					$dailyAccount->credit_transaction()->where('creator_id',$this->id)->sum('amount');
//			}
			
			//F[Az!9ve-]_7bL7%
//
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
