<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DeliveryMan extends BaseModel
	{
		
		protected $guarded = [];
		
		public function orders()
		{
			return $this->morphMany(Order::class, 'shippable');
		}
		
	}
