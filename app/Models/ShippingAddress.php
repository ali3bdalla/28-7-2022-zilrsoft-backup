<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed id
 */
class ShippingAddress extends BaseModel
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id')->with('country');
    }

}
