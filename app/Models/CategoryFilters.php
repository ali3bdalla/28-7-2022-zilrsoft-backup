<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryFilters extends BaseModel
{
    use \App\Traits\OrganizationTarget;
    protected $guarded = [];

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id');
    }
}
