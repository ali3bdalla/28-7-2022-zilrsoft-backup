<?php

namespace App\Models;

use App\Jobs\External\Smsa\GetShippmentStatusJob;

/**
 * @property mixed delivery_man_id
 * @property mixed order
 */
class ShippingTransaction extends BaseModel
{

    protected $guarded = [];

    protected $appends = ['delivery_time'];

    /**
     * @return string
     */
    public function getDeliveryTimeAttribute(): string
    {
        if ($this->shipped_at && $this->delivered_at) {
            return $this->delivered_at->diffInMinutes($this->shipped_at);
        }

        return "";
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }


    public function getShippingStatusAttribute()
    {
        return GetShippmentStatusJob::dispatch($this);
    }

    //
}
