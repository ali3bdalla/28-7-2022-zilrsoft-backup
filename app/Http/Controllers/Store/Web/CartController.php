<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {

        $data = [];

        if (auth('client')->check()) {
            $data['shippingAddresses'] = $request->user('client')->shippingAddresses()->with('city')->get(); //)
        }

        $data['cities'] = City::where('country_id', 1)->get();

        return Inertia::render(
            'Cart/Index',
            $data
        );
    }

    public function redirectToLogin(Request  $request)
    {
        $request->session()->put('url.intended', '/web/cart');
        return redirect('/web/sign_in');
    }
}
