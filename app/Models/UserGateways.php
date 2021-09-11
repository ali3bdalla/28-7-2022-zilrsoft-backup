<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string detail
 */
class UserGateways extends BaseModel
{

    protected $guarded = [];

    protected $appends = [
        'locale_name'
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getLocaleNameAttribute()
    {
        return $this->getOriginal("detail");
    }

}
