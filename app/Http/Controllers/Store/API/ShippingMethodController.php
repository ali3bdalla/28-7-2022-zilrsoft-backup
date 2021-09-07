<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\ShippingMethodCity;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    //
    public function index(Request $request)
    {
        $shippingMethodsIds = ShippingMethodCity::where('city_id', $request->input('city_id'))->pluck('shipping_method_id')->toArray();
        return ShippingMethod::find($shippingMethodsIds);
    }
}
