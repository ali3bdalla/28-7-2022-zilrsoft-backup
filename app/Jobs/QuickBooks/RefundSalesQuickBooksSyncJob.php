<?php

namespace App\Jobs\QuickBooks;

use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Manager;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\RefundReceipt;

class RefundSalesQuickBooksSyncJob implements ShouldQueue
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

        if ($this->refundInvoice->quickbooks_id != null || !$this->refundInvoice->invoice_type->equals(InvoiceTypeEnum::return_sale()) || !$this->refundInvoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->refundInvoice->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $taxCode = collect(collect($quickBooksDataService->Query("Select * From TaxCode WHERE Active=true"))->offsetGet(0));
        $salesReceiptLines = $this->refundInvoice->items()->with("item")->whereHas("item", function ($query) {
            return $query->where('is_kit', false);
        })->get()->map(function (InvoiceItems $invoiceItems, $index) use ($taxCode) {
            $data = [
                "Description" => $invoiceItems->item->locale_name,
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "DiscountAmt" => $invoiceItems->discount,
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
                $data["SalesItemLineDetail"]["ItemRef"] = [
                    "name" => $invoiceItems->item->locale_name,
                    "value" => $invoiceItems->item->quickbooks_id
                ];

            }
            return $data;
        })->toArray();
        $data = [
            "ApplyTaxAfterDiscount" => true,
            "DocNumber" => $this->refundInvoice->invoice_number,
            "TotalAmt" => $this->refundInvoice->subtotal,
            "TxnDate" => Carbon::parse($this->refundInvoice->created_at)->toDateString(),
            "DepositToAccountRef" => [
                "value" => config('zilrsoft_quickbooks.cash_equivalents_account_id')
            ],
            "PaymentRefNum" => "#" . $this->refundInvoice->invoice_number,
            "Line" => $salesReceiptLines,
            "ClassRef" => [
                "value" => $this->refundInvoice->creator->quickbooks_class_id
            ]
        ];
        if ($this->refundInvoice->user && $this->refundInvoice->user->quickbooks_customer_id) {
            $data["CustomerRef"] = [
                "value" => $this->refundInvoice->user->quickbooks_customer_id
            ];
        }
        if ($this->refundInvoice->department->quickbooks_id) {
            $data["DepartmentRef"] = [
                "value" => $this->refundInvoice->department->quickbooks_id
            ];
        }
        $salesReceipt = RefundReceipt::create(
            $data
        );
        $createdQuickBooksInvoice = $quickBooksDataService->Add($salesReceipt);
        if ($createdQuickBooksInvoice) {
            $this->refundInvoice->update([
                'quickbooks_id' => $createdQuickBooksInvoice->Id
            ]);
            return;
        }

        $error = $quickBooksDataService->getLastError();
        if ($error) {
            if ($error->getIntuitErrorCode() == "6140") {
                $id = (string)Str::of($error->getIntuitErrorDetail())->after("TxnId=");
                if ($id && (int)($id)) {
                    $this->refundInvoice->update([
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
                $this->refundInvoice->toArray(),
                $this->refundInvoice->items()->pluck("id")->toArray()
            ]));
        }

    }
}
