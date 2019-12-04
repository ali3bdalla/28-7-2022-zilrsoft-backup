<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class UserGateways extends Model
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
//			return $this->bank;
			return $this->detail;

//			return $this->belongsTo(Bank::class,'bank_id');
		}
		
		//
	}
