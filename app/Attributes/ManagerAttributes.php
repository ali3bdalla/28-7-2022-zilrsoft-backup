<?php
	
	namespace App\Attributes;
	
	use App\Account;
	
	trait  ManagerAttributes
	{
		public function getLocaleNameAttribute()
		{
			
			if (app()->isLocale('ar'))
				return $this->name_ar;
			
			return $this->name;
		}
		
		public function canDo($option)
		{
//			return $this->permissions;
			return $this->can($option) == true ? 1 : 0;
			
		}
		
		public function get_active_manager_account_for($kind)
		
		{
			
			$account = Account::where('slug',$kind)->first();
//			if ($kind == 'cogs')
//				$account = Account::where('slug','cogs')->first();
//
//			if ($kind == 'stock')
//				$account = Account::where('slug','stock')->first();
//
			
			return $account;
		}
		
		public function manager_gateway($type)
		{
			return Account::find(3);
		}
		
		public function memebership()
		{
			return $this->user->is_supervisor ? 'Admin' : 'Employer';
		}
		
		public function manager_current_stock()
		{
			return Account::where("slug",'stock')->first();
		}
		
	}
	
	