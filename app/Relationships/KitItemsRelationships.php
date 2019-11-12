<?php
namespace App\Relationships;
use App\Item;

trait KitItemsRelationships {


    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }


	public function kit()
    {
		return $this->belongsTo(Item::class,'kit_id');
	}
}
