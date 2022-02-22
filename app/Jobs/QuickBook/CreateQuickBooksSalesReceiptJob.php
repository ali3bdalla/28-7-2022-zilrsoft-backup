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

        if (!$this->invoice->organization->has_quickbooks || !$this->manager->quickBooksToken || $this->invoice->is_draft) return;
        $quickBooks = new Client(config('quickbooks'), $this->manager->quickBooksToken);
        $quickBooksDataService = $quickBooks->getDataService();
        $castAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE Name='Cash and cash equivalents'"))->first());
        $taxAccount = collect(collect($quickBooksDataService->Query("SELECT Id FROM Account WHERE Type='Expenses' And Name='Loss on discontinued operations, net of tax'"))->offsetGet(0));
        $salesReceiptLines = $this->invoice->items()->with("item")->whereHas("item", function ($query) {
            return $query->where('is_kit', false);
        })->get()->map(function (InvoiceItems $invoiceItems, $index) {
            $data = [
                "Description" => $invoiceItems->item->locale_name,
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "DiscountAmt" => $invoiceItems->discount,
                    "Qty" => $invoiceItems->qty,
                    "UnitPrice" => $invoiceItems->price,

                ],
                "LineNum" => ($index + 1),
                "Amount" => $invoiceItems->total,
            ];
            if ($invoiceItems->item->quickbooks_id) {
                $data["SalesItemLineDetail"]["ItemRef"] = [
                    "value" => $invoiceItems->item->quickbooks_id
                ];
            }
            return $data;
        })->toArray();
        $data = [
            "ApplyTaxAfterDiscount" => true,
            "DocNumber" => $this->invoice->invoice_number,
            "TotalAmt" => $this->invoice->subtotal,
            "TxnDate" => Carbon::parse($this->invoice->created_at)->toDateString(),
            "ClassRef" => [
                "value" => Str::slug($this->manager->quickbooks_class_id)
            ],
            "TaxLine" => [
                [
                    "DetailType" => "TaxLineDetail",
                    "Amount" => $this->invoice->tax,
                    "TaxLineDetail" => [
                        "NetAmountTaxable" => $this->invoice->net,
                        "TaxPercent" => 15,
                        "TaxRateRef" => [
                            "value" => $taxAccount->Id
                        ],
                        "PercentBased" => true
                    ]
                ]
            ],
            "DepositToAccountRef" => [
                "value" => $castAccount->get("Id")
            ],
            "TxnTaxDetail" => [
                "TotalTax" => $this->invoice->tax
            ],
            "MetaData" => [
                "CreateTime" => $this->invoice->created_at,
                "LastUpdatedTime" => $this->invoice->updated_at
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
            dd($createdQuickBooksInvoice);
        } else {
            dd($quickBooksDataService->getLastError());
        }
    }
}
