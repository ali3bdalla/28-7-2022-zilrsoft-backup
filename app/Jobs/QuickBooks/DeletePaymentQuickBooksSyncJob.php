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
use Illuminate\Support\Str;
use QuickBooksOnline\API\Facades\Payment;

class DeletePaymentQuickBooksSyncJob implements ShouldQueue
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

        if ($this->voucher->quickbooks_id == null || !$this->voucher->payment_type->equals(VoucherTypeEnum::receipt()) || !$this->voucher->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->voucher->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);

        $data = [
            "Id" => $this->voucher->quickbooks_id,
            "SyncToken" => "0"
        ];

        $bill = Payment::create(
            $data
        );

        $created = $quickBooksDataService->Delete($bill);
        if ($created) {
            $this->voucher->update([
                'quickbooks_id' => null
            ]);
            return;
        }

        $error = $quickBooksDataService->getLastError();
        if ($error) {
            if ($error->getIntuitErrorCode() == "6140") {
                $id = (string)Str::of($error->getIntuitErrorDetail())->after("TxnId=");
                if ($id && (int)($id)) {
                    $this->voucher->update([
                        'quickbooks_id' => $id
                    ]);
                    return;
                }
            }
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
