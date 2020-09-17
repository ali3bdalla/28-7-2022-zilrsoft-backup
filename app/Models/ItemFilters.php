<?php
	
	namespace App\Models;
	

	class ItemFilters extends BaseModel
	{
		
		protected $guarded = [];
		
		//
		protected $casts = [
			'id' => 'integer',
			'filter_value' => 'integer',
			'filter_id' => 'integer',
			'item_id' => 'integer'
		
		];


        public function filter()
        {
            return $this->belongsTo(Filter::class,'filter_id');
		}

        public function value()
        {
            return $this->belongsTo(FilterValues::class,'filter_value');
        }

        public function item()
        {
            return $this->belongsTo(Item::class,'item_id');
        }

		
	}
