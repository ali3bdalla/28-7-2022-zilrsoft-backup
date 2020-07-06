<?php
	
	namespace App;
	
	use App\Attributes\FilterAttributes;
	use App\Relationships\FilterRelationships;

	class Filter extends BaseModel
	{
		//
		use FilterRelationships,FilterAttributes;
		
		protected $appends = [
			'locale_name',
		];
		protected $guarded = [
		];
		
//		protected static function boot()
//		{
//			parent::boot();
//
//		}
		
	}
