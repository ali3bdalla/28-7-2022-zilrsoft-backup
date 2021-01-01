<?php

namespace App\Http\Requests\Purchases;

use App\Http\Controllers\Api\Traits\ReturnInvoiceTraits;
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

    use ReturnInvoiceTraits;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
                'parent_id' => $purchaseInvoice->id
            ]);
            $invoice->purchase()->create([
                'receiver_id' => $purchaseInvoice->managed_by_id,
                'vendor_id' => $purchaseInvoice->user_id,
                'organization_id' => $authUser->organization_id,
                'vendor_invoice_id' => $purchaseInvoice->purchase->vendor_invoice_number,
                'invoice_type' => 'return_purchase',
                "prefix" => 'RPU-',
            ]);
            dispatch_now(new UpdateInvoiceNumberJob($invoice, 'RPU-'));
            dispatch_now(new StoreReturnPurchaseItemsJob($invoice, $purchaseInvoice, (array)$returnedItems));
            dispatch_now(new StoreReturnPurchasePaymentsJob($invoice, $this->input('methods')));
            dispatch_now(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            dispatch_now(new StoreReturnPurchaseTransactionsJob($invoice->fresh()));
            DB::commit();
            return response($invoice, 200);
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateSerials($items)
    {
        foreach ($items as $item) {
            $item = collect($item);
            $dbInvoiceItem = InvoiceItems::find($item->get('id'));
            $dbItem = $dbInvoiceItem->item;
            if ($dbItem->is_need_serial) {
                if (count($item->get("serials")) != (int)$item->get('returned_qty')) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match returned qty']);
                }
                foreach ($item['serials'] as $serial) {
                    dispatch_now(new ValidateItemSerialJob($dbItem, $serial, ['sold', 'return_purchase']));
                }
            }
        }

    }

}
