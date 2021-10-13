<?php

namespace App\Models;

use App\Jobs\External\Smsa\GetShippmentStatusJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Carbon;

/**
 * @property mixed delivery_man_id
 * @property mixed order
 * @property mixed shippingMethod
 * @property mixed shipped_at
 * @property mixed delivered_at
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

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }


    public function getShippingStatusAttribute(): PendingDispatch
    {
        return GetShippmentStatusJob::dispatch($this);
    }
}
