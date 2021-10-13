<?php

namespace App\Http\Controllers\App\API;

use App\Dto\InvoiceDto;
use App\Enums\InvoiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreDraftSaleRequest;
use App\Http\Requests\Sales\StoreReturnSaleRequest;
use App\Http\Requests\Sales\StoreSaleRequest;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryContract;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class SaleController extends Controller
{

    public function index(FetchSalesRequest $request)
    {
        return $request->getData();
    }

    /**
     * @throws ValidationException|Throwable
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
}
