<?php
	
	namespace App\Attributes;
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
		
	}
	
	
	// all type of roles
	
	// items
