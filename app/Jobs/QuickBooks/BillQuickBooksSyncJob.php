<?php

namespace App\Jobs\QuickBooks;

use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Manager;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\Bill;
class BillQuickBooksSyncJob implements ShouldQueue
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

        if ($this->invoice->user == null || $this->invoice->quickbooks_id != null || !$this->invoice->invoice_type->equals(InvoiceTypeEnum::purchase()) || !$this->invoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->invoice->is_draft) return "UnAuthorized";
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $this->manager
        ]);
        $taxCode = collect(collect($quickBooksDataService->Query("Select * From TaxCode WHERE Active=true"))->offsetGet(0));
        $billLines = $this->invoice->items()->with("item")->whereHas("item", function ($query) {
            return $query->where('is_kit', false);
        })->get()->map(function (InvoiceItems $invoiceItems, $index) use ($taxCode) {
            $data = [
                "Description" => $invoiceItems->item->locale_name,
                "DetailType" => "ItemBasedExpenseLineDetail",
                "ItemBasedExpenseLineDetail" => [
                    "TaxInclusiveAmt" => $invoiceItems->net,
                    "Qty" => $invoiceItems->qty,
                    "UnitPrice" => $invoiceItems->qty > 0 ? $invoiceItems->subtotal / $invoiceItems->qty : $invoiceItems->price,
                    "TaxCodeRef" => [
                        "value" => $taxCode->get("Id")
                    ],
                    "ClassRef" => [
                        "value" => $this->invoice->creator->quickbooks_class_id
                    ]
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
        $documentNumber = $this->invoice->invoice_number;
        if(Str::contains($documentNumber,"2021")) {
            $documentNumber = $documentNumber . " " . Str::random(3);
        }
        $data = [
            "DocNumber" => $documentNumber,
            "TotalAmt" => $this->invoice->subtotal,
            "TxnDate" => Carbon::parse($this->invoice->created_at)->toDateString(),
            "Line" => $billLines,
            "VendorRef" => [
                "value" => $this->invoice->user->quickbooks_vendor_id
            ]
        ];
        if ($this->manager->department->quickbooks_id) {
            $data["DepartmentRef"] = [
                "value" => $this->manager->department->quickbooks_id
            ];
        }
        $bill = Bill::create(
            $data
        );

        $createdQuickBooksInvoice = $quickBooksDataService->Add($bill);
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
