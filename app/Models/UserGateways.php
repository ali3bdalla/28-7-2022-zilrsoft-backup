<?php
	
	namespace App\Models;
	

	class UserGateways extends BaseModel
	{
		
		protected $guarded = [];
		
		protected $appends = [
			'locale_name'
		];
		
		public function bank()
		{
			return $this->belongsTo(Bank::class,'bank_id');
		}
		
		public function getLocaleNameAttribute()
		{
			
			if($this->bank)
			{
				return $this->bank->ar_name.' '.$this->detail;
			}

			return $this->detail;

		}
		
		//
	}
