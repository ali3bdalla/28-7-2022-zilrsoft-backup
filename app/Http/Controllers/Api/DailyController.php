<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Daily\StoreResellerAccountTransactionRequest;
use App\Http\Requests\Daily\StoreResellerClosingAccountsRequest;

class DailyController extends Controller
{
    //

    public function storeResellerClosingAccount(StoreResellerClosingAccountsRequest $request)
    {
        return $request->store();
    }


    public function storeResellerAccountTransaction(StoreResellerAccountTransactionRequest $request)
    {
        return $request->store();
    }
}
