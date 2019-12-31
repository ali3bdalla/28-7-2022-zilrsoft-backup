<?php
	
	namespace App\Attributes;
	
	use Carbon\Carbon;
	use Illuminate\Support\Facades\DB;
	
	trait UserAttributes
	{
		
		
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateString();
		}
		
		public function getLocaleNameAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->name_ar;
			
			
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
		
	}
