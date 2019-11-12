<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Gateway extends Model
	{
		
		protected $table = 'gateways';
		
		protected $guarded = [];
		protected $casts = [
			'is_need_banks' => 'boalean'
		];
		
		public function fields()
		{
			return $this->hasMany(GatewayFields::class,'gateway_id');
		}
		
		public function getNameAttribute($value)
		{
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			return $value;
		}
		
		public function payments()
		{
			return $this->hasMany(Payment::class,'gateway_id');
		}
		
		public function chart()
		{
			return $this->belongsTo(Chart::class,"chart_id");
		}
		
		
	}
