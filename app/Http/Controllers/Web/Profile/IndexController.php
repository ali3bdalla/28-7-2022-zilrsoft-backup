<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Profile\UpdateInformationRequest;
use App\Http\Requests\Store\Profile\UpdatePasswordRequest;
use App\Http\Requests\Store\Profile\UpdatePhoneNumberRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    //

    public function index(Request $request)
    {


        return Inertia::render('Profile/Index', [
            'user' => $request->user(),
            'shipping_addresses' => $request->user()->shippingAddresses()->with('city.country')->get(),
        ]);
    }

    public function updateInformation(UpdateInformationRequest $request)
    {
        $request->update();
        return Inertia::render('Common/ShowMessage');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->update();
        return Inertia::render('Common/ShowMessage');
    }

    public function updatePhoneNumber(UpdatePhoneNumberRequest $request)
    {
        return $request->change();

    }

}
