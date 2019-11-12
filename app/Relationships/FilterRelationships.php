<?php
namespace App\Relationships;

use App\Manager;
use App\FilterValues;
trait FilterRelationships {


	public function creator(){
		return $this->belongsTo(Manager::class,'creator_id');
	}


	public function categories(){
		return $this->belongsToMany(Filter::class,'category_filters','filter_id','category_id')->withTimestamps();
	}

	public function values()
	{
		return $this->hasMany(FilterValues::class,'filter_id')->orderByDesc('filter_values.updated_at');
	}
}