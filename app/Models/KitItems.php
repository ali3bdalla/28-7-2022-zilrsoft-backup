<?php

namespace App\Models;

class KitItems extends BaseModel
{


    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function kit()
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }
	
	
}
