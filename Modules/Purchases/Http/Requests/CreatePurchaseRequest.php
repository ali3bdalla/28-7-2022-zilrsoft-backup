<?php

namespace Modules\Purchases\Http\Requests;

use App\Events\Accounting\Invoice\PendingPurchaseInvoiceCreatedEvent;
use App\Invoice;
use App\ItemSerials;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Jobs\CreatePurchasesEntityTransactionsJob;
use Modules\Purchases\Jobs\CreatePurchaseItemsJob;
use Modules\Purchases\Jobs\DeletePendingPurchaseJob;
use Modules\Purchases\Jobs\EnsurePurchaseDataAreCorrectJob;
use Modules\Purchases\Jobs\UpdateInvoiceTotalsJob;

class CreatePurchaseRequest extends FormRequest
{
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
            'pending_purchase_id' => 'nullable|integer',
            'methods.*.id' => 'required|integer|exists:accounts,id',
            'items' => 'required|array',
            'items.*.id' => ['required', 'integer', 'exists:items,id'],
            'items.*.price' => 'required|numeric|min:0|',
//            'items.*.total' => 'required|numeric',
//            'items.*.tax' => 'required|numeric',
//            'items.*.subtotal' => 'required|numeric',
//            'items.*.net' => 'required|numeric',
            'items.*.discount' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric',
//            'items.*.price_with_tax' => 'required|numeric|min:0',
            'items.*.serials.*' => ['required', function ($attr, $value, $fail) {
                $serial = ItemSerials::where('serial', $value)->first();
                if (!empty($serial)) {
                    if (in_array($serial->current_status, ['saled', 'available', 'r_sale'])) {
                        $fail('this serial is already exists');
                    }
                }
            }],
            'vendor_inc_number' => 'required|string',
            'remaining' => 'nullable|numeric',
            'expenses' => 'nullable|array',
            'expenses.*.id' => 'integer|required|exists:expenses',
            'expenses.*.is_open' => 'boolean|required',
            'expenses.*.is_apended_to_net' => 'boolean|required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create purchase');
    }

    public function store()
    {
        DB::beginTransaction();
        try {
//            dd($this->all());
            $authUser = auth()->user();
            $invoiceType = $this->user()->can('confirm purchase') ? 'purchase' : 'pending_purchase';
            $invoicePrefix = $invoiceType == 'purchase' ? 'PU-' : 'PPU-';
            $invoice = Invoice::create([
                'invoice_type' => $invoiceType,
                'notes' => "",
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'parent_invoice_id' => 0,
                'is_deleted' => false
            ]);
            $invoice->purchase()->create([
                'receiver_id' => $authUser->id,
                'vendor_id' => $this->input('vendor_id'),
                'organization_id' => $authUser->organization_id,
                'vendor_inc_number' => $this->input('vendor_inc_number'),
                'invoice_type' => $invoiceType,
                "prefix" => $invoicePrefix
            ]);
            $expenses = $this->getExpenses();
            $expensesAmount = (float)collect($expenses)->sum('amount');
            dispatch(new CreatePurchaseItemsJob($invoice, $this->input('items'), $this->input('methods'), $expenses));
            dispatch(new UpdateInvoiceTotalsJob($invoice, $expensesAmount));

            if (!$this->user()->can('confirm purchase')) {
                event(new PendingPurchaseInvoiceCreatedEvent($invoice->fresh()));
            } else {
                dispatch(new CreatePurchasesEntityTransactionsJob($invoice, $this->input('methods'), $expenses,$this->input('items')));
                dispatch(new EnsurePurchaseDataAreCorrectJob($invoice));
                dispatch(new DeletePendingPurchaseJob($this->input('pending_purchase_id')));
            }
            DB::commit();
            return response($invoice, 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getExpenses()
    {
        $expensesArray = [];
        if (!empty($this->has('expenses') && $this->filled("expenses"))) {
            foreach ($this->input("expenses") as $expense) {
                if ($expense['amount'] > 0) {
                    $expensesArray[] = $expense;
                }
            }
        }
        return $expensesArray;
    }


}
