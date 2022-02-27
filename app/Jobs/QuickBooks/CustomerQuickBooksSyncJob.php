<?php

namespace App\Jobs\QuickBooks;

use App\Models\Manager;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use QuickBooksOnline\API\Facades\Customer;

class CustomerQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private Manager $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Manager $manager)
    {
        //
        $this->user = $user;
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
        if ($this->user->quickbooks_customer_id != null || !$this->user->organization->has_quickbooks || !$this->manager->quickBooksToken) return;
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $quickBooksCustomer = Customer::create([
            "FullyQualifiedName" => $this->user->locale_name,
            "PrimaryEmailAddr" => [
                "Address" => $this->user->email_address
            ],
            "DisplayName" => $this->user->locale_name,
            "Title" => $this->user->user_title,
            "FamilyName" => $this->user->name,
            "MiddleName" => $this->user->ar_name,
            "PrimaryPhone" => [
                "FreeFormNumber" => $this->user->phone_number
            ],
            "MetaData" => [
                "CreateTime" => $this->user->created_at,
                "LastUpdatedTime" => $this->user->updated_at
            ],
        ]);
        $customer = $quickBooksDataService->Add($quickBooksCustomer);
        if ($customer) {
            $this->user->update([
                'quickbooks_customer_id' => $customer->Id
            ]);
            return;
        }
        $error = $quickBooksDataService->getLastError();
        if ($error) {
            throw  new \Exception(json_encode([
                $error->getIntuitErrorMessage(),
                $error->getIntuitErrorDetail(),
                $error->getIntuitErrorElement(),
                $error->getIntuitErrorCode(),
            ]));
        }

    }
}
