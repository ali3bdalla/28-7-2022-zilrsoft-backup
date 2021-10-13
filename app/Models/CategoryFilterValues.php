<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryFilterValues extends BaseModel
{
    protected $guarded = [];

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(FilterValues::class, 'value_id');
    }
}
