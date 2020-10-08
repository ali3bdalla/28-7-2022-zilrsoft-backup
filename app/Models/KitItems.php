<?php

namespace App\Models;

use App\Relationships\KitItemsRelationships;
class KitItems extends BaseModel
{

    use KitItemsRelationships;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function kit()
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }

    //
}
