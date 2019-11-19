<?php
	
	namespace App\Attributes;
	use App\Account;
	
	trait ManagerAttributes
	{
		
		public function memebership()
		{
			return $this->user->is_supervisor ? 'Admin' : 'Employer';
		}
		
		
		
		// to check if the current user
		// all roles
		// create_category
		// update_category
		// create-item
		// create-kit
		
		//manage-filter
		public function isAuthorizedTo($task = 'create-user')
		{
			//$this->user->is_supervisor ||
			// return true;
			return $this->user->is_supervisor ||
				($this->user->is_manager && $this->roles->pluck('slug')->contains($task));
		}
		
		public function manager_current_stock()
		{
			return Account::where("slug",'stock')->first();
		}
		
		
		
	}
	
	
	// all type of roles
	
	// items
