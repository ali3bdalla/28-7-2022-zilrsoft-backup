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
use QuickBooksOnline\API\Exception\IdsException;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;
use Spinen\QuickBooks\Client;

class ItemSyncJob implements ShouldQueue
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
     * @throws IdsException
     * @throws SdkException
     * @throws ServiceException
     */
    public function handle()
    {
        try {
            if (!$this->item->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
            $quickBooks = new Client(config('quickbooks'), $this->manager->quickBooksToken);
            $quickBooksDataService = $quickBooks->getDataService();
            $salesIncomesAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE AccountSubType='SalesOfProductIncome'"))->offsetGet(0));
            $expensesAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE AccountSubType='SuppliesMaterialsCogs'"))->offsetGet(0));
            $data = [
                "TrackQtyOnHand" => $this->item->is_service == false,
                "Name" => $this->item->locale_name,
                "QtyOnHand" => $this->item->available_qty,
                "Sku" => $this->item->barcode,
                "InvStartDate" => Carbon::parse($this->item->created_at)->format("Y-m-d"),
                "Type" => $this->item->is_service ? "Service" : "Inventory",
                "UnitPrice" => $this->item->price,
                "PurchaseCost" => $this->item->cost,
                "Taxable" => true,
                "ExpenseAccountRef" => [
                    "value" => $expensesAccount->get('Id')
                ],
                "IncomeAccountRef" => [
                    "value" => $salesIncomesAccount->get('Id')
                ],
            ];
            if (!$this->item->is_service) {
                $data["AssetAccountRef"]["value"] = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE AccountSubType='Inventory'"))->offsetGet(0))->get("Id");
            }
            $quickBooksItem = \QuickBooksOnline\API\Facades\Item::create($data);
            $item = $quickBooks->getDataService()->Add($quickBooksItem);
            if ($item) {
                $this->item->update([
                    'quickbooks_id' => $item->Id
                ]);
            }
        } catch (Exception $exception) {

        }
    }
}
