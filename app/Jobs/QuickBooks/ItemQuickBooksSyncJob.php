<?php

namespace App\Jobs\QuickBooks;

use App\Models\Item;
use App\Models\Manager;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

class ItemQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Item $item;
    private Manager $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Item $item, Manager $manager)
    {
        //
        $this->item = $item;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if (!$this->item->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $data = [
            "TrackQtyOnHand" => $this->item->is_service == false,
            "Name" => $this->item->locale_name,
            "FullyQualifiedName" => $this->item->locale_description,
            "QtyOnHand" => $this->item->available_qty,
            "Sku" => $this->item->barcode,
            "InvStartDate" => Carbon::parse($this->item->created_at)->format("Y-m-d"),
            "Type" => $this->item->is_service ? "Service" : "Inventory",
            "UnitPrice" => $this->item->price,
            "PurchaseCost" => $this->item->cost,
            "Taxable" => true,
            "ExpenseAccountRef" => [
                "value" => config('zilrsoft_quickbooks.expenses_account_id')
            ],
            "IncomeAccountRef" => [
                "value" => config('zilrsoft_quickbooks.incomes_account_id')
            ],
            "MetaData" => [
                "CreateTime" => $this->item->created_at,
                "LastUpdatedTime" => $this->item->updated_at
            ],
        ];
        if ($this->item->category && $this->item->category->quickbooks_id) {
            $data = array_merge($data, [
                "SubItem" => true,
                "ParentRef" => [
                    "value" => $this->item->category->quickbooks_id
                ]
            ]);
        }
        if (!$this->item->is_service) {
            $data["AssetAccountRef"]["value"] = config('zilrsoft_quickbooks.inventory_account_id');
        }
        $quickBooksItem = \QuickBooksOnline\API\Facades\Item::create($data);
        $item = $quickBooksDataService->Add($quickBooksItem);
        if ($item) {
            $this->item->update([
                'quickbooks_id' => $item->Id
            ]);
            return;
        }
        $error = $quickBooksDataService->getLastError();
        if ($error) {
            throw  ValidationException::withMessages([
                $error->getIntuitErrorMessage(),
                $error->getIntuitErrorDetail(),
                $error->getIntuitErrorElement(),
                $error->getIntuitErrorCode(),
            ]);
        }
    }
}
