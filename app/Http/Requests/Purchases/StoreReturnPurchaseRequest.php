<?php

namespace App\Http\Requests\Purchases;

use App\Http\Controllers\App\API\Traits\ReturnInvoiceTraits;
use App\Jobs\Accounting\Purchase\StoreReturnPurchaseTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Purchases\Items\StoreReturnPurchaseItemsJob;
use App\Jobs\Purchases\Payment\StoreReturnPurchasePaymentsJob;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreReturnPurchaseRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'integer|required|organization_exists:App\Models\InvoiceItems,id',
            'items.*.returned_qty' => 'required',
            'items.*.serials' => 'array',
            //            'items.*.serials.*' => 'required|organization_exists:App\Models\ItemSerials,serial',
            'methods' => 'array',
            'methods.*.id' => 'required|integer|organization_exists:App\Models\Account,id',
            'methods.*.amount' => 'required|numeric',
            //            'methods.*.id' => 'integer|required|organization_exists:App\Models\Account,id',
        ];
    }

    public function store(Invoice $purchaseInvoice)
    {
        DB::beginTransaction();
        try {
            $this->validateItemsBelongsToInvoice($purchaseInvoice, $this->input('items'));
            $returnedItems = $this->getReturnItems($this->input('items'));

            $this->validateSerials($returnedItems);
            $this->validateReturnedItemsData($returnedItems);
            $authUser = auth()->user();
            $invoice = Invoice::create([
                'invoice_type' => 'return_purchase',
                'creator_id' => $authUser->id,
                'organization_id' => $purchaseInvoice->organization_id,
                'branch_id' => $purchaseInvoice->branch_id,
                'department_id' => $purchaseInvoice->department_id,
                'user_id' => $purchaseInvoice->user_id,
                'managed_by_id' => $purchaseInvoice->managed_by_id,
                'parent_id' => $purchaseInvoice->id,
                'vendor_invoice_id' => $purchaseInvoice->vendor_invoice_id,
            ]);
            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'RPU-'));
            dispatch_sync(new StoreReturnPurchaseItemsJob($invoice, $purchaseInvoice, (array)$returnedItems));
            dispatch_sync(new StoreReturnPurchasePaymentsJob($invoice, $this->input('methods')));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            dispatch_sync(new StoreReturnPurchaseTransactionsJob($invoice->fresh()));
            DB::commit();
            return response($invoice, 200);
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }

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

    private function validateSerials($items)
    {
        foreach ($items as $item) {
            $item = collect($item);
            $dbInvoiceItem = InvoiceItems::find($item->get('id'));
            $dbItem = $dbInvoiceItem->item;
            if ($dbItem->is_need_serial) {
                if (count($item->get("serials")) != (float)$item->get('returned_qty')) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match returned qty']);
                }
                foreach ($item['serials'] as $serial) {
                    dispatch_sync(new ValidateItemSerialJob($dbItem, $serial, ['sold', 'return_purchase']));
                }
            }
        }
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
