<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class OrderActivity extends BaseModel
	{
		
		protected $guarded = [];
		
		public function order()
		{
			return $this->belongsTo(Order::class, 'order_id');
		}
		
		
		public function doable()
		{
			return $this->morphTo('doable');
		}
		//
	}
