<?php

namespace App\Jobs\QuickBooks;

use App\Enums\VoucherTypeEnum;
use App\Models\Manager;
use App\Models\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\BillPayment;

class DeleteBillPaymentQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Voucher $voucher;
    private Manager $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Voucher $voucher, Manager $manager)
    {
        $this->voucher = $voucher;
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

        if ($this->voucher->quickbooks_id == null || !$this->voucher->payment_type->equals(VoucherTypeEnum::payment()) || !$this->voucher->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->voucher->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $data = [
            "Id" => $this->voucher->quickbooks_id,
            "SyncToken" => "0"
        ];

        $bill = BillPayment::create(
            $data
        );
        $created = $quickBooksDataService->Delete($bill);
        if ($created) {
            $this->voucher->update([
               'quickbooks_id'
            ]);
            return;
        }

        $error = $quickBooksDataService->getLastError();
        if ($error) {
            throw  new Exception(json_encode([
                $error->getIntuitErrorMessage(),
                $error->getIntuitErrorDetail(),
                $error->getIntuitErrorElement(),
                $error->getIntuitErrorCode(),
                $this->voucher->toArray()
            ]));
        }

    }
}
