<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Inventory\FetchInventoryAdjustmentsRequest;
use App\Http\Requests\Inventories\StoreBeginningInventoryRequest;
use App\Http\Requests\Inventories\StoreInventoryAdjustmentRequest;
use App\Repository\InventoryRepositoryContract;
use Exception;

class InventoryController extends Controller
{
    //
    private InventoryRepositoryContract $inventoryRepositoryContract;

    public function __construct(InventoryRepositoryContract $inventoryRepositoryContract)
    {
        $this->inventoryRepositoryContract = $inventoryRepositoryContract;
    }

    public function storeBeginning(StoreBeginningInventoryRequest $request)
    {
        return $request->store();
    }


    /**
     * @throws Exception
     */
    public function storeAdjustment(StoreInventoryAdjustmentRequest $request)
    {
        $items = $request->getItems();
        $createdAt = $request->getCreatedAt();
        $this->inventoryRepositoryContract->createAdjustment($items);
//        return $request->store();
    }


    public function adjustments(FetchInventoryAdjustmentsRequest $request)
    {
        return $request->getData();
    }
}
