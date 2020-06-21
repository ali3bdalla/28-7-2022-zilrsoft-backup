<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Scopes\OrganizationScopeForRelationships;


class CategoryFilters extends BaseModel
{
	public function filter()
	{
		return $this->belongsTo(Filter::class,'filter_id');
	}


    //
}
