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
use QuickBooksOnline\API\Facades\Payment;

class PaymentQuickBooksSyncJob implements ShouldQueue
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

        if ($this->voucher->quickbooks_id != null || !$this->voucher->payment_type->equals(VoucherTypeEnum::receipt()) || !$this->voucher->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->voucher->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);

        $data = [
//            "ClassRef" => [
//                "value" => $this->manager->quickbooks_class_id
//            ],
            "TotalAmt" => $this->voucher->amount,
            "Id" => $this->voucher->id,
            "TxnDate" => Carbon::parse($this->voucher->created_at)->toDateString(),
            "MetaData" => [
                "CreateTime" => $this->voucher->created_at,
                "LastUpdatedTime" => $this->voucher->updated_at
            ],
            "DepositToAccountRef" => [
                "value" => config('zilrsoft_quickbooks.cash_equivalents_account_id')
            ],
            "CustomerRef" => [
                "value" => $this->voucher->user->quickbooks_customer_id
            ]
        ];

        $bill = Payment::create(
            $data
        );

        $created = $quickBooksDataService->Add($bill);
        if ($created) {
            $this->voucher->update([
                'quickbooks_id' => $created->Id
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
