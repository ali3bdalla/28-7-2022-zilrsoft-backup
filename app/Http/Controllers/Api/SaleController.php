<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreSaleRequest;

class SaleController extends Controller
{
    //

    public function index(FetchSalesRequest $request)
    {
        return $request->getData();
    }

    public function store(StoreSaleRequest $request)
    {
        return $request->store();
    }
}
