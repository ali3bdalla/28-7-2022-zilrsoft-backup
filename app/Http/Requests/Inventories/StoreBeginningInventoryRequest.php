<?php

namespace App\Http\Requests\Inventories;

use App\Jobs\Accounting\BeginningInventory\StoreBeginningInventoryTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Purchases\Items\StorePurchaseItemsJob;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreBeginningInventoryRequest extends FormRequest
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
            'items' => 'required|array',
            'items.*.id' => ['required', 'integer', 'organization_exists:App\Models\Item,id'],
            'items.*.purchase_price' => 'required|numeric|min:0|purchaseItemPrice',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.serials' => 'array',
            'items.*.serials.*' => 'required',
        ];
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $this->validateSerials();

            $authUser = auth()->user();
            $beginningInventoryUser = User::where([
                ['user_slug', 'beginning-inventory'],
                ['is_system_user', true]
            ])->first();;
            $invoice = Invoice::create([
                'invoice_type' => 'beginning_inventory',
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'user_id' => $beginningInventoryUser->id,
                'managed_by_id' => $authUser->id,
            ]);
            $invoice->purchase()->create([
                'receiver_id' => $authUser->id,
                'vendor_id' => $beginningInventoryUser->id,
                'organization_id' => $authUser->organization_id,
                'vendor_invoice_id' => '',
                'invoice_type' => 'beginning_inventory',
                "prefix" => 'BGI-',
            ]);
            dispatch_now(new UpdateInvoiceNumberJob($invoice, 'BGI-'));
            dispatch_now(new StorePurchaseItemsJob($invoice, (array)$this->input('items'),false,'beginning_inventory'));
            dispatch_now(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            dispatch_now(new StoreBeginningInventoryTransactionsJob($invoice->fresh()));
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
                    dispatch_now(new ValidateItemSerialJob($dbItem, $serial, ['in_stock', 'return_sale']));
                }
            }
        }

    }

}
