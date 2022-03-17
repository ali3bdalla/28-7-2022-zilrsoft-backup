<?php

namespace App\Jobs\QuickBooks;

use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Manager;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\SalesReceipt;
use QuickBooksOnline\API\Facades\Invoice as QuickbooksInvoice;

class SalesQuickBooksSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $invoice;
    private Manager $manager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, Manager $manager)
    {
        $this->invoice = $invoice;
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

        if ($this->invoice->quickbooks_id != null || !$this->invoice->invoice_type->equals(InvoiceTypeEnum::sale()) || !$this->invoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->invoice->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $taxCode = collect(collect($quickBooksDataService->Query("Select * From TaxCode WHERE Active=true"))->offsetGet(0));
        $salesReceiptLines = $this->invoice->items()->with("item")->whereHas("item", function ($query) {
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
        $documentNumber = $this->invoice->invoice_number;
        if (Str::contains($documentNumber, "2021")) {
            $documentNumber = $documentNumber . " " . Str::random(3);
        }
        $depositAmount = $this->invoice->payments()->sum("amount");
        if ((int)round($depositAmount) >= (int)round($this->invoice->net)) {
            $depositAmount = $this->invoice->net;
        }

        $data = [
            "ApplyTaxAfterDiscount" => true,
            "DocNumber" => $documentNumber,
            "TotalAmt" => $this->invoice->subtotal,
            "TxnDate" => Carbon::parse($this->invoice->created_at)->toDateString(),
            "DepositToAccountRef" => [
                "value" => config('zilrsoft_quickbooks.cash_equivalents_account_id')
            ],
            "PaymentRefNum" => "#" . $this->invoice->invoice_number,
            "Line" => $salesReceiptLines,
            "ClassRef" => [
                "value" => $this->invoice->creator->quickbooks_class_id
            ],
            "Deposit" => $depositAmount
        ];
        if ($this->invoice->user->quickbooks_customer_id) {
            $data["CustomerRef"] = [
                "value" => $this->invoice->user->quickbooks_customer_id
            ];
        }
        $salesReceipt = QuickbooksInvoice::create(
            $data
        );
        $createdQuickBooksInvoice = $quickBooksDataService->Add($salesReceipt);
        if ($createdQuickBooksInvoice) {
            $this->invoice->update([
                'quickbooks_id' => $createdQuickBooksInvoice->Id
            ]);
            return;
        }

        $error = $quickBooksDataService->getLastError();
        if ($error) {
            if ($error->getIntuitErrorCode() == "6140") {
                $id = (string)Str::of($error->getIntuitErrorDetail())->after("TxnId=");
                if ($id && (int)($id)) {
                    $this->invoice->update([
                        'quickbooks_id' => $id
                    ]);
                    return;
                }
            }
            throw  new Exception(json_encode([
                $error->getIntuitTid(),
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
