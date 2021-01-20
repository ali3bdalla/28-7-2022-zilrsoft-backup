<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DeliveryMan extends BaseModel
	{
		
		protected $guarded = [];

		protected $appends = ['locale_name'];
		
		public function orders()
		{
			return $this->hasMany(Order::class, 'delivery_man_id');
		}

		public function getLocaleNameAttribute()
		{
			return $this->first_name . " " . $this->last_name;
		}


		public function verfications()
		{
			return $this->morphMany(Verfication::class,'verifiable');
		}

		public function shippingMethod()
		{
			return $this->belongsTo(ShippingMethod::class,'shipping_method_id');
		}
		
	}
