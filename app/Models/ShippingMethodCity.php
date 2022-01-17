<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethodCity extends Model
{
    protected $guarded = [];
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}
