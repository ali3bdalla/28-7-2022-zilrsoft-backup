<?php

namespace App\Repository\Eloquent;

use App\Jobs\Accounting\Inventory\Adjustment\StoreInventoryAdjustmentTransactionsJob;
use App\Jobs\Inventory\Adjustments\Items\StoreInventoryAdjustmentItemsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Models\Invoice;
use App\Models\User;
use App\Repository\InventoryRepositoryContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryRepository extends BaseRepository implements InventoryRepositoryContract
{

    public function __construct()
    {

    }


    public function createAdjustment(array $items)
    {
        return DB::transaction(function () use ($items) {
            $user = User::where([
                ['user_slug', 'beginning-inventory'],
                ['is_system_user', true]
            ])->first();
            $authUser = Auth::user();
            $invoice = Invoice::create(
                [
                    'invoice_type' => 'inventory_adjustment',
                    'notes' => "",
                    'creator_id' => $authUser->id,
                    'organization_id' => $authUser->organization_id,
                    'branch_id' => $authUser->branch_id,
                    'department_id' => $authUser->department_id,
                    'user_id' => $user->id,
                    'managed_by_id' => $authUser->id,
                ]
            );
            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'IA'));
            dispatch_sync(new StoreInventoryAdjustmentItemsJob($invoice, $items, false));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            dispatch_sync(new StoreInventoryAdjustmentTransactionsJob($invoice->fresh()));
            return $invoice;
        });


    }
}
