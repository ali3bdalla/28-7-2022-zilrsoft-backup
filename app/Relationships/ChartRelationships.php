<?php
	
	
	namespace App\Relationships;
	
	
	use App\Chart;
	use App\ChartFields;
	use App\DynamicFields;
	use App\Gateway;
	use App\Item;
	use App\Payment;
	
	trait ChartRelationships
	{
		
		public function children()
		{
			return $this->hasMany(Chart::class,'parent_id');
		}
		
		public function parent()
		{
			return $this->belongsTo(Chart::class,'parent_id');
		}
		
		
		
		public function fields()
		{
			return $this->morphMany(DynamicFields::class,'dynamicable');
		}
		
		public function payments()
		{
			return $this->hasMany(Payment::class,'chart_id');
		}
		
		
		public function items()
		{
			return $this->hasMany(Item::class,'chart_id');
		}
		
		public function gateway()
		{
			return $this->hasOne(Gateway::class,'chart_id');
		}
	}