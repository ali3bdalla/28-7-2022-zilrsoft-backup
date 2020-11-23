<?php
	
	namespace App\Models;
	
	
	class ShippingAddress extends BaseModel
	{
		protected $guarded = [];
		
		public function user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}
		
		public function country()
		{
			return $this->belongsTo(Country::class, 'country_id');
		}
		
	}
