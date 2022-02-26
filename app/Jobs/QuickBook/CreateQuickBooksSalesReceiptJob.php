<?php

namespace App\Jobs\QuickBook;

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
use Illuminate\Support\Str;
use QuickBooksOnline\API\Facades\SalesReceipt;
use Spinen\QuickBooks\Client;

class CreateQuickBooksSalesReceiptJob implements ShouldQueue
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

        if (!$this->invoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->invoice->is_draft) return "UnAuthorized";
        $quickBooks = new Client(config('quickbooks'), $this->manager->quickBooksToken);
        $quickBooksDataService = $quickBooks->getDataService();

        $castAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE Name='Cash and cash equivalents'"))->offsetGet(0));
        $taxAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE Name='Loss on discontinued operations, net of tax'"))->offsetGet(0));

        $salesReceiptLines = $this->invoice->items()->with("item")->whereHas("item", function ($query) {
            return $query->where('is_kit', false);
        })->get()->map(function (InvoiceItems $invoiceItems, $index) {
            return [
                "Description" => $invoiceItems->item->locale_name,
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "DiscountAmt" => $invoiceItems->discount,
                    "Qty" => $invoiceItems->qty,
                    "UnitPrice" => $invoiceItems->price,
                    "TaxCodeRef" => [
                        "value" => "28"
                    ],
                    "ItemRef" => [
                        "name" => $invoiceItems->item->locale_name,
                        "value" => $invoiceItems->item->quickbooks_id
                    ]
                ],
                "Id" => $invoiceItems->id,
                "LineNum" => ($index + 1),
                "Amount" => $invoiceItems->total,
            ];
        })->toArray();
        $data = [
            "ApplyTaxAfterDiscount" => true,
            "DocNumber" => $this->invoice->invoice_number,
            "TotalAmt" => $this->invoice->subtotal,
            "TxnDate" => Carbon::parse($this->invoice->created_at)->toDateString(),
            "ClassRef" => [
                "value" => Str::slug($this->manager->quickbooks_class_id)
            ],
            "DepositToAccountRef" => [
                "value" => $castAccount->get("Id")
            ],
            "PaymentRefNum" => "#" . $this->invoice->invoice_number,
            "Line" => $salesReceiptLines,
        ];

        $data["CustomerRef"] = [
            "value" => $this->invoice->user->quickbooks_customer_id
        ];

        $salesReceipt = SalesReceipt::create(
            $data
        );
        $createdQuickBooksInvoice = $quickBooksDataService->Add($salesReceipt);
        if ($createdQuickBooksInvoice) {
            $this->invoice->update([
                'quickbooks_id' => $createdQuickBooksInvoice->Id
            ]);
            dd('created', $createdQuickBooksInvoice);
        } else {
            dd($quickBooksDataService->getLastError());
        }
    }
}
