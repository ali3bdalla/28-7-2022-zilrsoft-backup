<?php

namespace App\Http\Requests\Purchases;

use App\Jobs\Accounting\Purchase\StorePurchaseTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Purchases\Items\StorePurchaseItemsJob;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreDraftPurchaseRequest extends FormRequest
{
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
            'receiver_id' => 'required|integer|exists:managers,id',
            'vendor_id' => 'required|integer|exists:users,id',
            'items' => 'required|array',
            'items.*.id' => ['required', 'integer', 'organization_exists:App\Models\Item,id'],
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|quantity|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.serials' => 'array',
            'items.*.serials.*' => 'required',
            'vendor_invoice_id' => 'required|string',
        ];
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $this->validateSerials();
            $authUser = auth()->user();
            $invoice = Invoice::create([
                'invoice_type' => 'purchase',
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'user_id' => $this->vendor_id,
                'is_draft' => true,
                'managed_by_id' => $this->receiver_id,
            ]);

            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'DPU-'));
            dispatch_sync(new StorePurchaseItemsJob($invoice, (array) $this->input('items'), true));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            DB::commit();
            return response($invoice, 200);
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateSerials()
    {
        foreach ($this->input('items') as $item) {
            $dbItem = Item::find($item['id']);
            if ($dbItem->is_need_serial) {
                if (count($item['serials']) != $item['qty']) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match qty']);
                }

                foreach ($item['serials'] as $serial) {
                    dispatch_sync(new ValidateItemSerialJob($dbItem, $serial, ['in_stock', 'return_sale']));
                }
            }
        }
    }
}
