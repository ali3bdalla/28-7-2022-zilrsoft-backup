<?php
namespace App\Relationships;

use App\Item;

trait  KitDataRelationships {

    public function kit(){
        return $this->belongsTo(Item::class,'kit_id');
    }

}
