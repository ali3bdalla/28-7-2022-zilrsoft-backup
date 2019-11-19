<?php
	
	namespace App\Attributes;
	
	use Illuminate\Support\Facades\DB;
	
	trait UserAttributes
	{
		
		public function getLocaleNameAttribute()
		{
			return $this->name;
		}
		public function scopeVendors($query)
		{
			return $query->where([
				['is_vendor',true],
				['is_system_user',false]
			]);
		}
		
		public function scopeClients($query)
		{
			return $query->where([
				['is_client',true],
				['is_system_user',false],
			]);
		}
		
		public function scopeManagers($query)
		{
			return $query->where('is_manager',true)->with('manager');
		}
		
		public function updateUserBalance($option,$value)
		{
			if ($option == 'add' && is_numeric($value)){
				$this->update([
					'balance' => DB::raw("balance + $value")
				]);
			}else
				if ($option == 'sub' && is_numeric($value)){
					$this->update([
						'balance' => DB::raw("balance - $value")
					]);
				}
		}
		
		public function membership_type()
		{
			
			$memeberships = [];
			
			if ($this->is_supervisor){
				$memeberships[] = '<span class="badge badge-dark badge-type">supervisor</span>';
				return $memeberships;
			}
			
			if ($this->is_manager){
				$memeberships[] = '<span class="badge badge-info badge-type">manger</span>';
			}
			
			
			if ($this->is_client)
				$memeberships[] = '<span class="badge badge-primary  badge-type">client</span>';
			
			
			if ($this->is_vendor)
				$memeberships[] = '<span class="badge badge-success  badge-type">vendor</span>';
			
			
			if ($this->is_supplier)
				$memeberships[] = '<span class="badge badge-warning  badge-type">supplier</span>';
			
			
			return $memeberships;
		}
		
		public function creator_user()
		{
			if ($this->is_supervisor){
				return 'him self';
			}
			return '';
		}
		
	}
