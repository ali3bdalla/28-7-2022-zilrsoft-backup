<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends BaseModel
{

    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['cities_ids','locale_name'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'shippable');
    }

    public function cities()
    {
        return $this->hasMany(ShippingMethodCity::class, 'shipping_method_id');
    }


    public function transactions()
    {
        return $this->hasMany(ShippingTransaction::class,'shipping_method_id');
    }

    public function deliveryMen()
    {
        return $this->hasMany(DeliveryMan::class, 'shipping_method_id');
    }

    public function getCitiesIdsAttribute()
    {
        return $this->cities()->pluck('city_id')->toArray();
    }
    //
}
