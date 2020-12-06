<?php

namespace App\Http\Controllers\BackEnd\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Store\Shipping\StoreShippingMethodDeliveryManRequest;
use App\Http\Requests\Backend\Store\Shipping\UpdateShippingMethodRequest;
use App\Models\City;
use App\Models\Item;
use App\Models\ShippingMethod;

class ShippingController extends Controller
{
    public function index()
    {
        return view('backend.store.shipping.index', [
            'shippingMethods' => ShippingMethod::paginate(20)
        ]);
    }


    public function edit(ShippingMethod $shipping)
    {
        $expensesItems = Item::where('is_expense', true)->get();


        return view('backend.store.shipping.edit', [
            'deliveryMen' => $shipping->deliveryMen()->get(),
            'shipping' => $shipping,
            'expenses' => $expensesItems,
            'citiesList' => City::all(),
            'shippingCities' => $shipping->cities()->pluck('id')->toArray()
        ]);
    }


    public function update(ShippingMethod $shipping, UpdateShippingMethodRequest $request)
    {
        return $request->update($shipping);
    }


    public function storeDeliveryMan(ShippingMethod $shipping, StoreShippingMethodDeliveryManRequest $request)
    {
        return $request->store($shipping);
    }
}