<?php
namespace App\Relationships;

use App\Models\Item;

trait  KitDataRelationships {

    public function kit(){
        return $this->belongsTo(Item::class,'kit_id');
    }

}
