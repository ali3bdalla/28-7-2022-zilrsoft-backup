<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Daily\StoreResellerClosingAccountsRequest;
use Illuminate\Http\Request;

class DailyController extends Controller
{
    //

    public function storeResellerClosingAccount(StoreResellerClosingAccountsRequest $request)
    {
        return $request->store();
    }
}
