<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemFilters extends BaseModel
{

    protected $guarded = [];

    //
    protected $casts = [
        'id' => 'integer',
        'filter_value' => 'integer',
        'filter_id' => 'integer',
        'item_id' => 'integer'

    ];


    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(FilterValues::class, 'filter_value');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


}
