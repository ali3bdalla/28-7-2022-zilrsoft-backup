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

class BillPaymentQuickBooksSyncJob implements ShouldQueue
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

        if ($this->voucher->quickbooks_id != null || !$this->voucher->payment_type->equals(VoucherTypeEnum::payment()) || !$this->voucher->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->voucher->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
//        $releatedTranascation =
        $data = [
            "TotalAmt" => $this->voucher->amount,
            "PrivateNote" => $this->voucher->description,
            "DocNumber" => $this->voucher->id,
            "TxnDate" => Carbon::parse($this->voucher->created_at)->toDateString(),
            "MetaData" => [
                "CreateTime" => $this->voucher->created_at,
                "LastUpdatedTime" => $this->voucher->updated_at
            ],
            "Line" => [
                [
                    "Amount" => $this->voucher->amount,
//                    "LineNum" => $this->voucher->id,
//                    "Description" => $this->voucher->description,

//                    "LinkedTxn" => [
//                        [
//                            "TxnId" => "10427",
//                            "TxnType" => "Bill"
//                        ]
//                    ]
                ]],
            "PayType" => "Check",
            "CheckPayment" => [
                "BankAccountRef" => [
                    "value" => config('zilrsoft_quickbooks.cash_equivalents_account_id')
                ]
            ],
            "VendorRef" => [
                "value" => $this->voucher->user->quickbooks_vendor_id
            ]
        ];

        $bill = BillPayment::create(
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
        dd($error);
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
