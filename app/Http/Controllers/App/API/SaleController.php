<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreDraftSaleRequest;
use App\Http\Requests\Sales\StoreReturnSaleRequest;
use App\Http\Requests\Sales\StoreSaleRequest;
use App\Http\Requests\Sales\UpdateAliceNameRequest;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryContract;
use Exception;
use Illuminate\Validation\ValidationException;
use Throwable;

class SaleController extends Controller
{

    public function __construct(InvoiceRepositoryContract $invoiceRepositoryContract)
    {
    }

    public function index(FetchSalesRequest $request)
    {
        return $request->getData();
    }

    /**
     * @throws Throwable|ValidationException
     */
    public function store(StoreSaleRequest $request)
    {
        return $request->store();
    }

    /**
     * @throws Exception
     */
    public function storeDraft(StoreDraftSaleRequest $request)
    {
        return $request->store();
    }

    /**
     * @throws ValidationException
     */
    public function storeReturnSale(Invoice $sale, StoreReturnSaleRequest $request)
    {
        return $request->store($sale);
    }

    public function show(Invoice $purchase): Invoice
    {
        return $purchase;
    }

    public function updateAliceName(Invoice $sale, UpdateAliceNameRequest $updateAliceNameRequest)
    {
        $sale->update([
            'user_alice_name' => $updateAliceNameRequest->getAliceName(),
        ]);
    }
}
