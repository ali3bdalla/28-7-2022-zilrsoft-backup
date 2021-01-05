<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ShippingAddress\StoreShippingAddressRequest;
use App\Models\City;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class ShippingAddressController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $cities = City::where('country_id', 1)->get();
        return Inertia::render('Web/Profile/CreateShippingAddress', [
            'cities' => $cities
        ]);
    }


    /**
     * @param StoreShippingAddressRequest $request
     * @return mixed
     */
    public function store(StoreShippingAddressRequest $request)
    {
        return $request->store();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param ShippingAddress $shippingAddress
     * @return void
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        //
    }
}
