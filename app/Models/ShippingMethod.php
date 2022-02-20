<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property float max_base_weight
 * @property float max_base_weight_price
 * @property float kg_rate_after_max_price
 * @property float kg_rate_after_max_cost
 * @property float max_base_weight_cost
 * @property integer id
 */
class ShippingMethod extends BaseModel
{

    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['locale_name'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'shippable');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ShippingTransaction::class, 'shipping_method_id');
    }

    public function deliveryMen(): HasMany
    {
        return $this->hasMany(DeliveryMan::class, 'shipping_method_id');
    }

    public function getCitiesIdsAttribute(): array
    {
        return $this->cities()->pluck('city_id')->toArray();
    }

    public function cities(): HasMany
    {
        return $this->hasMany(ShippingMethodCity::class, 'shipping_method_id');
    }

    public function getShippingCost(float $totalOrderWeight)
    {
        $maxShippingMethodWeight = $this->max_base_weight;
        $shippingCost = $this->max_base_weight_cost;
        if ($totalOrderWeight > $maxShippingMethodWeight) {
            $kgAfterBase = $totalOrderWeight - $maxShippingMethodWeight;
            $shippingCost = $shippingCost + ($kgAfterBase * $this->kg_rate_after_max_cost);
        }
        return $shippingCost;
    }

    public function getShippingAmount(float $weight = 0, float $discount = 0): float
    {
        $kgAfterBase = $weight - $this->max_base_weight;
        $amount = $this->max_base_weight_price;
        if ($kgAfterBase > 0)
            $amount = $amount + (float)($kgAfterBase * $this->kg_rate_after_max_price);
        if ($discount > $amount) return 0;
        return $amount - $discount;
    }

    /*
     * TODO: remove this  method, make sure noting is broken
     * */
    public function getBaseWeightPrice(): float
    {
        return $this->max_base_weight_price;
    }
}
