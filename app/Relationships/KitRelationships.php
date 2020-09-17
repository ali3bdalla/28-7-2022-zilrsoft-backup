<?php
namespace App\Relationships;
use App\Models\KitData;
use App\Models\KitItems;

trait KitRelationships {


	public function items(){
		return $this->hasMany(KitItems::class,'kit_id')->with('item');
	}


	public function data(){
	    return $this->hasOne(KitData::class,'kit_id');
    }


	public function scopeKits($query)
	{
		return $query->where('is_kit',true);
	}
}
