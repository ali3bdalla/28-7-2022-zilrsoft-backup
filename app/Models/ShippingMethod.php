<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class ShippingMethod extends BaseModel
	{
		
		use SoftDeletes;
		
		protected $guarded = [];
		
		
	
		public function item()
		{
			return $this->belongsTo(Item::class);
		}
		
		public function orders()
		{
			return $this->morphMany(Order::class,'shippable');
		}
		//
	}
