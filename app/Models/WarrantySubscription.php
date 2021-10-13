<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed name
 * @property mixed ar_name
 */
class WarrantySubscription extends BaseModel
{
    protected $guarded = [];

    protected $appends = [
        'locale_name',
    ];
    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }

        return $this->name;
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'warranty_subscription_id');
    }
}
