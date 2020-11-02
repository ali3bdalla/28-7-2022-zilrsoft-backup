<?php
	
	namespace App\Models;
	
	
	class ShippingAddress extends BaseModel
	{
		protected $guarded = [];
		
		public function user()
		{
			return $this->belongsTo(User::class);
		}
		
	}
