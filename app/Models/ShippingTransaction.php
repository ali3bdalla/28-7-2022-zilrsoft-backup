<?php

namespace App\Models;

use App\Jobs\External\Smsa\GetShippmentStatusJob;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed delivery_man_id
 * @property mixed order
 */
class ShippingTransaction extends BaseModel
{

    protected $guarded = [];

    // protected $appends = ['shipping_status'];


    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class,'shipping_method_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
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
