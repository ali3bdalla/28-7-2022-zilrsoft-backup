<?php

namespace App\Jobs\QuickBooks;

use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Manager;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\RefundReceipt;
use QuickBooksOnline\API\Facades\VendorCredit;

class RefundBillQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $refundInvoice;
    private Manager $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $refundInvoice, Manager $manager)
    {
        $this->refundInvoice = $refundInvoice;
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

        if ($this->refundInvoice->quickbooks_id != null || !$this->refundInvoice->invoice_type->equals(InvoiceTypeEnum::return_purchase()) || !$this->refundInvoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->refundInvoice->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $taxCode = collect(collect($quickBooksDataService->Query("Select * From TaxCode WHERE Active=true"))->offsetGet(0));
        $billLines = $this->refundInvoice->items()->with("item")->whereHas("item", function ($query) {
            return $query->where('is_kit', false);
        })->get()->map(function (InvoiceItems $invoiceItems, $index) use ($taxCode) {
            $data = [
                "Description" => $invoiceItems->item->locale_name,
                "DetailType" => "ItemBasedExpenseLineDetail",
                "ItemBasedExpenseLineDetail" => [
                    "Qty" => $invoiceItems->qty,
                    "UnitPrice" => $invoiceItems->price,
                    "TaxCodeRef" => [
                        "value" => $taxCode->get("Id")
                    ],
                ],
                "Id" => $invoiceItems->id,
                "LineNum" => ($index + 1),
                "Amount" => $invoiceItems->total,
            ];
            if ($invoiceItems->item->quickbooks_id) {
                $data["ItemBasedExpenseLineDetail"]["ItemRef"] = [
                    "name" => $invoiceItems->item->locale_name,
                    "value" => $invoiceItems->item->quickbooks_id
                ];

            }
            return $data;
        })->toArray();
        $data = [
            "DocNumber" => $this->invoice->invoice_number,
            "TotalAmt" => $this->invoice->subtotal,
            "TxnDate" => Carbon::parse($this->invoice->created_at)->toDateString(),
            "Line" => $billLines,
            "VendorRef" => [
                "value" => $this->invoice->user->quickbooks_vendor_id
            ]
        ];

        $vendorCredit = VendorCredit::create(
            $data
        );
        $createdQuickBooksInvoice = $quickBooksDataService->Add($vendorCredit);
        if ($createdQuickBooksInvoice) {
            $this->refundInvoice->update([
                'quickbooks_id' => $createdQuickBooksInvoice->Id
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
                $this->invoice->toArray(),
                $this->invoice->items()->pluck("id")->toArray()
            ]));
        }

    }
}
