<?php

namespace Modules\Sales\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Sales\Http\Requests\CreateReturnRequest;

class RetrunController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param CreateReturnRequest $request
     * @param Invoice $sale
     * @return Invoice
     * @throws ValidationException
     */
    public function return(CreateReturnRequest $request, Invoice $sale)
    {
        return $request->store($sale);
//        return view('sales::index');
    }

}
