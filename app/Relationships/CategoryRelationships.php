<?php

namespace App\Relationships;
use \App\Models\Category;
use \App\Models\Filter;
use App\Models\Item;

trait CategoryRelationships {
	
	public function children(){
		return $this->hasMany(Category::class,'parent_id');
	}



	public function filters(){
		return $this->belongsToMany(Filter::class,'category_filters','category_id','filter_id')->withTimestamps()->orderBy('category_filters.id','asc');
	}

	public function items()
	{
		return $this->hasMany(Item::class,'category_id');
	}

}