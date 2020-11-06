<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Order extends BaseModel
	{
		use SoftDeletes;
		
		
		protected $guarded;
		
		public function user()
		{
			return $this->belongsTo(User::class);
		}
		
		public function shippingAddress()
		{
			return $this->belongsTo(ShippingAddress::class,'shipping_address_id');
		}
		
		public function shippingMethod()
		{
			return $this->belongsTo(ShippingMethod::class,'shipping_method_id');
			
		}
		
	}
