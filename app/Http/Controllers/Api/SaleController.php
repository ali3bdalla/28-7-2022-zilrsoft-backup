<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\StoreDraftPurchaseRequest;
use App\Http\Requests\Purchases\StoreReturnPurchaseRequest as StoreReturnPurchaseRequestAlias;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreReturnSaleRequest;
use App\Http\Requests\Sales\StoreSaleRequest;
use App\Models\Invoice;

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

    public function storeDraft(StoreDraftSaleRequest $request)
    {
        return $request->store();
    }


    public function storeReturnSale(Invoice $slae, StoreReturnSaleRequest $request)
    {
        return $request->store($slae);
    }

    public function show(Invoice $purchase)
    {
        return $purchase;
    }
}
