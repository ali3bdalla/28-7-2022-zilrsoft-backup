<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @property mixed id
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed phone_number
 */
class DeliveryMan extends BaseModel
{

    protected $guarded = [];
    protected $appends = ['locale_name'];

    public function getInternationalPhoneNumberAttribute(): string
    {
        return (string)PhoneNumber::make($this->phone_number, 'SA');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_man_id');
    }

    public function getLocaleNameAttribute(): string
    {
        return $this->first_name . " " . $this->last_name;
    }


    public function verfications(): MorphMany
    {
        return $this->morphMany(Verfication::class, 'verifiable');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

}
