<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\FetchPurchasesRequest;
use App\Http\Requests\Purchases\StoreDraftPurchaseRequest;
use App\Http\Requests\Purchases\StorePurchaseRequest;
use App\Http\Requests\Purchases\StoreReturnPurchaseRequest as StoreReturnPurchaseRequestAlias;
use App\Models\Invoice;

class PurchaseController extends Controller
{


    /**
     * @param FetchPurchasesRequest $request
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

    public function storeDraft(StoreDraftPurchaseRequest $request)
    {
        return $request->store();
    }


    public function storeReturnPurchase(Invoice $purchase, StoreReturnPurchaseRequestAlias $request)
    {
        return $request->store($purchase);
    }

    public function show(Invoice $purchase)
    {
        return $purchase;
    }
}