<?php

namespace Modules\Purchases\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Purchases\Http\Requests\CreateReturnRequest;

class ReturnController extends Controller
{


    /**
     * Show the form for creating a new resource.
     * @param Invoice $purchase
     * @param CreateReturnRequest $request
     * @return Response
     */
    public function return(Invoice $purchase, CreateReturnRequest $request)
    {
        return $request->store($purchase);
    }

}
