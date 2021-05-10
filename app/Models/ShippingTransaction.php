<?php

namespace App\Models;

use App\Jobs\External\Smsa\GetShippmentStatusJob;
use Illuminate\Support\Carbon;

/**
 * @property mixed delivery_man_id
 * @property mixed order
 * @property mixed shippingMethod
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
            return Carbon::parse($this->delivered_at)->diffInMinutes($this->shipped_at) . ' min';
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
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
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
