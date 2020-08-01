<?php
	
	namespace App\Attributes;
	
	use App\Account;
	
	trait  ManagerAttributes
	{


        public function _getStockAccount()
        {
            return Account::where("slug",'stock')->first();
	    }


		public function getLocaleNameAttribute()
		{
			
			if (app()->isLocale('ar'))
				return $this->name_ar;
			
			return $this->name;
		}
		
		public function canDo($option)
		{
			return $this->can($option) == true ? 1 : 0;
			
		}
		
		public function get_active_manager_account_for($kind)
		
		{
			$account = Account::where('slug',$kind)->first();
			return $account;
		}
		
		public function manager_gateway($type)
		{
			return Account::find(3);
		}
		
		public function manager_current_stock()
		{
			return Account::where("slug",'stock')->first();
		}
		
		/**
		 * @param string $account_slug
		 *  to return manager stock depend on the accounts list
		 *
		 * @return mixed
		 */
		public function toGetManagerAccount($account_slug = "")
		{
			return Account::where("slug",$account_slug)->first();
		}
	}
	
	