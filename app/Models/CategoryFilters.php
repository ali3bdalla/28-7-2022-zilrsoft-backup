<?php

namespace App\Models;



class CategoryFilters extends BaseModel
{

    protected $guarded = [];
	public function filter()
	{
		return $this->belongsTo(Filter::class,'filter_id');
	}
}
