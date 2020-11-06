<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class ShippingMethod extends Model
	{
		
		use SoftDeletes;
		protected $guarded = [];
		
		public function item()
		{
			return $this->belongsTo(Item::class);
		}
		//
	}
