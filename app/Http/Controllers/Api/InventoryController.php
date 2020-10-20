<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\StoreBeginningInventoryRequest;
use App\Http\Requests\Inventories\StoreInventoryAdjustmentRequest;

class InventoryController extends Controller
{
    //

    public function storeBeginning(StoreBeginningInventoryRequest $request)
    {
        return $request->store();
    }


    public function storeAdjustment(StoreInventoryAdjustmentRequest $request)
    {
        return $request->store();
    }
}
