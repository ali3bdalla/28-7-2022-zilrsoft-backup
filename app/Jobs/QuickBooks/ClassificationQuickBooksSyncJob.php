<?php

namespace App\Jobs\QuickBooks;

use App\Models\Manager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use QuickBooksOnline\API\Facades\FacadeHelper;

class ClassificationQuickBooksSyncJob implements ShouldQueue
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
     * @throws Exception
     */
    public function handle()
    {
        if ($this->class->quickbooks_class_id != null || !$this->class->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $quickBooksItem = FacadeHelper::reflectArrayToObject("Class", [
            "Name" => Str::slug($this->class->name . " " .  $this->class->id),
            "FullyQualifiedName" => $this->class->locale_name,
        ]);
        $class = $quickBooksDataService->Add($quickBooksItem);
        if ($class) {
            $this->class->update([
                'quickbooks_class_id' => $class->Id
            ]);
            return;
        }

        $error = $quickBooksDataService->getLastError();
        if ($error) {
            if ($error->getIntuitErrorCode() == "6140") {
                $id = (string)Str::of($error->getIntuitErrorDetail())->after("TxnId=");
                if ($id && (int)($id)) {
                    $this->class->update([
                        'quickbooks_class_id' => $id
                    ]);
                    return;
                }
            }

            throw  new \Exception(json_encode([
                $error->getIntuitErrorMessage(),
                $error->getIntuitErrorDetail(),
                $error->getIntuitErrorElement(),
                $error->getIntuitErrorCode(),
            ]));
        }

    }
}
