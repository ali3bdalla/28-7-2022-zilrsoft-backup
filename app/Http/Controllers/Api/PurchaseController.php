<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\FetchPurchasesRequest;
use App\Http\Requests\Purchases\StorePurchaseRequest;

class PurchaseController extends Controller
{
    

       /**
     * @param DatatableRequest $request
     *
     * @return mixed
     */
    public function index(FetchPurchasesRequest $request)
    {
        return $request->getData();
    }


    public function store(StorePurchaseRequest $request)
    {
        return $request->store();
    }
}
