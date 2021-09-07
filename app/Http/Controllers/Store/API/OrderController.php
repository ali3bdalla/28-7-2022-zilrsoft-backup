<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        return $request->user()->orders()->with("shippingMethod")->orderBy('id', 'desc')->paginate(50);
    }

    /**
     * @param StoreOrderRequest $storeOrderRequest
     * @return StoreOrderRequest|Application|RedirectResponse|Redirector
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        return $storeOrderRequest->store();
    }
}
