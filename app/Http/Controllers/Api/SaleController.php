<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;

class SaleController extends Controller
{
    //

    public function index(FetchSalesRequest $request)
    {
        return $request->getData();
    }
}
