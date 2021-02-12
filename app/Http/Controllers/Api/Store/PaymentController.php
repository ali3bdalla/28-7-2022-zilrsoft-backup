<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->orders()->with("shippingMethod")->orderBy('id', 'desc')->paginate(50);
    }
}
