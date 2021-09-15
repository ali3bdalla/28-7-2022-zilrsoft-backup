<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\FetchSalesRequest;
use App\Http\Requests\Sales\StoreDraftSaleRequest;
use App\Http\Requests\Sales\StoreReturnSaleRequest;
use App\Http\Requests\Sales\StoreSaleRequest;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryContract;
use Exception;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    private InvoiceRepositoryContract $invoiceRepositoryContract;

    public function __construct(InvoiceRepositoryContract $invoiceRepositoryContract)
    {
        $this->invoiceRepositoryContract = $invoiceRepositoryContract;
    }

    public function index(FetchSalesRequest $request)
    {
//        return $this->invoiceRepositoryContract->getInvoicesPagination(new InvoiceSearchValueObject(
//            $request->getInvoiceType(),
//            $request->getIsDraft(),
//            $request->getStartDate(),
//            $request->getEndDate(),
//            $request->getCreators(),
//            $request->getClients(),
//            $request->getSalesmen(),
//            $request->getAliceName(),
//            $request->getTitle(),
//            $request->getNet(),
//            $request->getTax(),
//            $request->getTotal(),
//            $request->getDiscount(),
//            $request->getSubtotal(),
//        ));
        return $request->getData();
    }

    /**
     * @throws ValidationException
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
