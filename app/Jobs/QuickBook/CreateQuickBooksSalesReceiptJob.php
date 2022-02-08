<?php

namespace App\Jobs\QuickBook;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\SalesReceipt;

class CreateQuickBooksSalesReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Invoice $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        //
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        [
//    {
//        "Description": "Pest Control Services",
//      "DetailType": "SalesItemLineDetail",
//      "SalesItemLineDetail": {
//        "TaxCodeRef": {
//            "value": "NON"
//        },
//        "Qty": 1,
//        "UnitPrice": 35,
//        "ItemRef": {
//            "name": "Pest Control",
//          "value": "10"
//        }
//      },
//      "LineNum": 1,
//      "Amount": 35.0,
//      "Id": "1"
//    }
//  ]
        $quickBooks = app('QuickBooks');
        dd($quickBooks->getDataService()->Query("SELECT * FROM Item"));
        $this->invoice->items()->get()->each(function (InvoiceItems $item) {
            dd(Item::create([
                "Name" => $item->item->locale_name,
                "Sku" => $item->item->barcode,
                "Description" => $item->item->locale_description,
                "Type" => "Inventory"
            ]));
//            return [
//                "Description" => $item->item->locale_description,
//                "Amount" => $item->subtotal,
//                "Id" => "3",
//                "DetailType" => "SalesItemLineDetail",
//                "SalesItemLineDetail" => [
//                    "DiscountAmt" => $item->discount,
//                    "TaxInclusiveAmt" => $item->net,
//                    "Qty" => $item->qty,
//                    "UnitPrice" => $item->price,
//                    "ItemRef" => [
//                        "name" => $item->item->locale_name,
//                        "value" => $item->item->id
//                    ]
//                ]
//            ];

        });
//        SalesReceipt::create(
//            [
//                "DocNumber" => $this->invoice->invoice_number,
//                "Line" => $items
//            ]
//        );
    }
}
