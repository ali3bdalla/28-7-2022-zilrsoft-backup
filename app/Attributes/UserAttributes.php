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
				$memeberships[] = '<span class="tag ">مدير النظام</span>';
				return $memeberships;
			}
			
			if ($this->is_manager){
				$memeberships[] = '<span class="tag tag-primary">مستخدم</span>';
			}
			
			
			if ($this->is_client)
				$memeberships[] = '<span class="tag is-primary ">عميل</span>';
			
			
			if ($this->is_vendor)
				$memeberships[] = '<span class="tag is-success ">مورد</span>';
			
			
			if ($this->is_supplier)
				$memeberships[] = '<span class="tag is-dark ">مزود خدمات</span>';
			
			
			return $memeberships;
		}
		
		public function creator_user()
		{
			if ($this->is_supervisor){
				return 'مدير النظام';
			}
			return $this->creator->name;
		}
		
	}
