<?php
	
	namespace App;
	

	class ItemFilters extends BaseModel
	{
		
		protected $fillable = [
			'organization_id',
			'creator_id',
			'filter_id',
			'filter_value',
			'item_id',
		];
		
		//
		protected $casts = [
			'id' => 'integer',
			'filter_value' => 'integer',
			'filter_id' => 'integer',
			'item_id' => 'integer'
		
		];


		
	}
