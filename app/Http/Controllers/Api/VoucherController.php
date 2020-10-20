<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\FetchVouchersRequest;
use App\Http\Requests\Vouchers\StoreVoucherRequest;

class VoucherController extends Controller
{

    public function index(FetchVouchersRequest $request)
    {
        return $request->getData();
    }


    public function store( StoreVoucherRequest $request )
    {
        return $request->store();
    }
    //
}
