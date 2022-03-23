<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Inventory\FetchInventoryAdjustmentsRequest;
use App\Http\Requests\Inventories\StoreBeginningInventoryRequest;
use App\Http\Requests\Inventories\StoreInventoryAdjustmentRequest;
use App\Jobs\QuickBooks\ItemInventoryAdjustmentQuickBooksJob;
use App\Jobs\QuickBooks\ItemQuickBooksSyncJob;
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
        $invoice = $this->inventoryRepositoryContract->createAdjustment($items);
        foreach ($invoice->items()->with("creator", "item")->get() as $item) {
            $this->dispatch(new ItemQuickBooksSyncJob($item->item, $item->creator));
        }
        return $invoice;
    }


    public function adjustments(FetchInventoryAdjustmentsRequest $request)
    {
        return $request->getData();
    }
}
