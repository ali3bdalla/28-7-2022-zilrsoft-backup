<?php

namespace App\Jobs;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use QuickBooksOnline\API\Facades\FacadeHelper;
use Spinen\QuickBooks\Client;

class SyncQuickBooksClassJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Manager $class;
    private Manager $manager;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Manager $class, Manager $manager)
    {
        //
        $this->class = $class;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (!$this->class->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
            $quickBooks = new Client(config('quickbooks'), $this->manager->quickBooksToken);
            $quickBooksDataService = $quickBooks->getDataService();
            $quickBooksItem = FacadeHelper::reflectArrayToObject("Class", [
                "Name" => Str::slug($this->manager->name),
                "FullyQualifiedName" => $this->manager->locale_name,
            ]);
            $class = $quickBooksDataService->Add($quickBooksItem);
            if ($class) {
                $this->manager->update([
                    'quickbooks_class_id' => $class->Id
                ]);
            }
        } catch (Exception $exception) {

        }
    }
}
