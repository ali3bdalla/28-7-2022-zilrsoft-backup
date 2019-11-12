<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class GatewayFields extends Model
	{
		//
		protected $guarded = [];
		
		public function gateway()
		{
			return $this->belongsTo(Gateway::class,'gateway_id');
		}
		
		public function getPlaceholderAttribute($value)
		{
			if (app()->isLocale('ar')){
				return $this->ar_placeholder;
			}
			
			return $value;
		}
		
	}
