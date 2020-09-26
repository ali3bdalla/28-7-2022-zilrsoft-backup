<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreBeginningInventoryRequest;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //

    public function storeBeginning(StoreBeginningInventoryRequest $request)
    {
        return $request->store();
    }
}
