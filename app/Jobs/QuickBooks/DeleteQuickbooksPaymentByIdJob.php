<?php

namespace App\Jobs\QuickBooks;

use App\Models\Manager;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use QuickBooksOnline\API\Facades\Payment;

class DeleteQuickbooksPaymentByIdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Manager $manager;
    private $quickbooksId;
    private $SyncToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($quickbooksId,$SyncToken, Manager $manager)
    {
        $this->manager = $manager;
        $this->quickbooksId = $quickbooksId;
        $this->SyncToken = $SyncToken;
    }

    public function handle()
    {
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);

        $data = [
            "Id" => $this->quickbooksId,
            "SyncToken" => $this->SyncToken
        ];
        $bill = Payment::create(
            $data
        );
        sleep(1);
        $quickBooksDataService->Delete($bill);
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