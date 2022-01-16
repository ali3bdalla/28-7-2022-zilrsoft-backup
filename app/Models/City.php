<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends BaseModel
{
    protected $guarded = [];
    protected $appends = ['locale_name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function allowedShippingMethods()
    {
        return $this->hasMany(ShippingMethodCity::class, 'city_id')->with('shippingMethod')->whereHas("shippingMethod");
    }
}
