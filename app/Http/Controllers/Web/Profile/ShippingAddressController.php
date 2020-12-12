<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer|exists:cities,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|mobileNumber',
//            'building_number' => 'nullable',
            'description' => 'required|string|max:100',
//            'street_name' => 'string',
//            'zip_code' => 'nullable|integer'
        ]);

        return $request->user('client')->shippingAddresses()->create($request->only('city_id', 'first_name', 'last_name', 'phone_number', 'building_number', 'description', 'street_name', 'zip_code',));


    }

    /**
     * Display the specified resource.
     *
     * @param ShippingAddress $shippingAddress
     * @return Response
     */
    public function show(ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ShippingAddress $shippingAddress
     * @return Response
     */
    public function edit(ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ShippingAddress $shippingAddress
     * @return Response
     */
    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ShippingAddress $shippingAddress
     * @return Response
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        //
    }
}
