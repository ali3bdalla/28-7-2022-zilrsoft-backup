<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\City;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Cart/Index');
    }

    public function shippingMethod()
    {
        if (Cart::isEmpty()) abort(404);
        return Inertia::render("Cart/ShippingMethod", [
            'cities_with_shipping_methods' => City::query()->with(["allowedShippingMethods" => function ($query) {
                return $query->distinct();
            }])->get()
        ]);
    }
    public function shippingAddress(Request $request)
    {
        if (Cart::isEmpty() || !Cart::hasShippingMethod()) abort(404, "Cart Is Empty");
        if (!Auth::guard('client')->check()) {
            $request->session()->put('url.intended', '/web/cart/shipping_address');
            return redirect('/web/sign_in');
        }

        if (!Cart::needShippingAddress()) {
            return redirect('/web/cart/checkout');
        }
        return Inertia::render("Cart/shippingAddress", [
            'shippingAddresses' => ShippingAddress::where([
                ['city_id', Cart::getSessionCart()->city_id],
                ['user_id', Auth::guard('client')->user()->id]
            ])->with("city")->get()
        ]);
    }

    public function checkout()
    {
        if (!Cart::isReady() || !Cart::canBeHandled()) {
            return back();
        }
        return Inertia::render("Cart/Checkout");
    }
}
