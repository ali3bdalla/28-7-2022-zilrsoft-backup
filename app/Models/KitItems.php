<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitItems extends BaseModel
{


    protected $guarded = [];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function kit(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }


}
