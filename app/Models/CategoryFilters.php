<?php

namespace App\Models;



class CategoryFilters extends BaseModel
{
	public function filter()
	{
		return $this->belongsTo(Filter::class,'filter_id');
	}


    //
}
