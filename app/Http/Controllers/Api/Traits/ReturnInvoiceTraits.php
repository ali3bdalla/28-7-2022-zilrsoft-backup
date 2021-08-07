<?php


namespace App\Http\Controllers\Api\Traits;


use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Validation\ValidationException;

trait ReturnInvoiceTraits
{

    /**
     * @param Invoice $invoice
     * @param $validateItem
     */
    private function validateItemsBelongsToInvoice(Invoice $invoice, $validateItem)
    {
        $actualInvoiceItems = $invoice->items()->pluck('id')->toArray();
        foreach ($validateItem as $item) {
            if (!in_array($item['id'], $actualInvoiceItems)) {
                $error = ValidationException::withMessages([
                    "invoice" => ['all items must belongs to current invoice'],
                ]);
                throw  $error;
            }
        }
    }


    private function getReturnItems($items = [])
    {
        $returnedItems = [];
        foreach ($items as $item) {
            if ((float)$item['returned_qty'] >= 1) {
                $returnedItems[] = $item;
            }
        }

        if (empty($returnedItems)) {
            $error = ValidationException::withMessages([
                "invoice" => ['returned items must be at lest one item'],
            ]);
            throw  $error;
        }
        return $returnedItems;
    }


    private function validateReturnedItemsData($items, $invoiceType = 'sale')
    {

        foreach ($items as $item) {
            $dbInvoiceItem = InvoiceItems::find($item['id']);
            $returnedQty = (float)$dbInvoiceItem->returned_qty + (float)$item['returned_qty'];
            if ($returnedQty > $dbInvoiceItem->qty) {
                $error = ValidationException::withMessages([
                    "items.returned_qty" => ['item qty is not enough'],
                ]);
                throw $error;
            }

            if (!$dbInvoiceItem->item->is_service && !$dbInvoiceItem->item->is_kit) {
                if ($dbInvoiceItem->invoice_type == 'purchase') {
                    if ($returnedQty > $dbInvoiceItem->item->available_qty) {
                        $error = ValidationException::withMessages([
                            "items.returned_qty" => ['item qty is not enough'],
                        ]);
                        throw $error;
                    }
                }
            }
        }
    }
}
