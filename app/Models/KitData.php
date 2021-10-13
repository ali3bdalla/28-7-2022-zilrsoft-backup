<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitData extends BaseModel
{
    protected $guarded = [];

    public function kit(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }


}


