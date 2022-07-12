<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreDraftSaleRequest;
use App\Http\Requests\Sales\StoreWarrantyTracingRequest;
use App\Http\Requests\Sales\StoreSaleRequest;
use App\Http\Requests\Sales\UpdateAliceNameRequest;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryContract;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Throwable;

class SaleController extends Controller
{

    public function __construct(InvoiceRepositoryContract $invoiceRepositoryContract)
    {
    }

    public function report(FetchSalesRequest $fetchSalesRequest)
    {
        $query = Invoice::query();
        if ($fetchSalesRequest->has('startDate') && $fetchSalesRequest->filled('startDate') && $fetchSalesRequest->has('endDate') && $fetchSalesRequest->filled('endDate')) {
            $_startDate = Carbon::parse($fetchSalesRequest->input('startDate'));
            $_endDate = Carbon::parse($fetchSalesRequest->input('endDate'));

            if ($_endDate === $_startDate) {
                $query = $query->whereDate('created_at', $_startDate);
            } else {
                $query = $query->whereBetween('created_at', [$_startDate, $_endDate,]);
            }
        }

        if ($fetchSalesRequest->has('invoice_type')) {
            $query = $query->where('invoice_type', $fetchSalesRequest->input('invoice_type'));
        }
        return ['net' => round($query->sum('net'), 2), 'total' => round($query->sum('total'), 2), 'subtotal' => round($query->sum('subtotal'), 2), 'discount' => round($query->sum('discount'), 2), 'tax' => round($query->sum('tax'), 2),];
    }

    public function warrantyTracing(FetchSalesRequest $request)
    {
        return $request->getWarrantyTracingData();
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
     * @throws Throwable
     */
    public function storeReturnSale(Invoice $sale, StoreWarrantyTracingRequest $request)
    {
        return $request->store($sale);
    }

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function storeWarrantyTracing(Invoice $sale, StoreWarrantyTracingRequest $request)
    {
        return $request->store($sale);
    }

    public function show(Invoice $purchase): Invoice
    {
        return $purchase;
    }

    public function updateAliceName(Invoice $sale, UpdateAliceNameRequest $updateAliceNameRequest)
    {
        $sale->update(['user_alice_name' => $updateAliceNameRequest->getAliceName(),]);
    }
}
