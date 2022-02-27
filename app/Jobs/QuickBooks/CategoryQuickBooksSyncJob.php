<?php

namespace App\Jobs\QuickBooks;

use App\Models\Category;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use QuickBooksOnline\API\Facades\Item;

class CategoryQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Category $category;
    private Manager $manager;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Category $category, Manager $manager)
    {
        //
        $this->category = $category;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ValidationException
     */
    public function handle()
    {
        if ($this->category->organization && !$this->category->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $data = [
            "TrackQtyOnHand" => false,
            "Name" => $this->category->locale_name,
            "InvStartDate" => Carbon::parse($this->category->created_at)->format("Y-m-d"),
            "Type" => "Category",
            "Taxable" => false,
            "MetaData" => [
                "CreateTime" => $this->category->created_at,
                "LastUpdatedTime" => $this->category->updated_at
            ],
        ];
        $quickBooksItem = Item::create($data);
        $item = $quickBooksDataService->Add($quickBooksItem);
        if ($item) {
            $this->category->update([
                'quickbooks_id' => $item->Id
            ]);
            return;
//            dd($item);
        }
        $error = $quickBooksDataService->getLastError();
        if ($error) {
            throw  new Exception([
                $error->getIntuitErrorMessage(),
                $error->getIntuitErrorDetail(),
                $error->getIntuitErrorElement(),
                $error->getIntuitErrorCode(),
            ]);
        }

    }
}
