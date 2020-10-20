<?php

namespace App\Models;


class KitData extends BaseModel
{
    protected $guarded = [];

    public function kit()
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }
    
    
}


