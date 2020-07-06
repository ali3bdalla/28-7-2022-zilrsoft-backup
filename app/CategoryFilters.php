<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CategoryFilters extends BaseModel
{
	public function filter()
	{
		return $this->belongsTo(Filter::class,'filter_id');
	}


    //
}
