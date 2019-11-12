<?php

namespace App\Relationships;
use \App\Category;
use \App\Filter;


trait CategoryRelationships {
	
	public function children(){
		return $this->hasMany(Category::class,'parent_id');
	}



	public function filters(){
		return $this->belongsToMany(Filter::class,'category_filters','category_id','filter_id')->withTimestamps()->orderBy('category_filters.id','asc');
	}

}