<?php

namespace App\Http\Requests\Accounting\Purchase;

use App\Models\Accounting\AmountsAccounting;
use App\Models\Accounting\ExpensesAccounting;
use App\Models\Accounting\IdentityAccounting;
use App\Models\Accounting\PaymentAccounting;
use App\Models\Accounting\TransactionAccounting;
use App\Events\Accounting\Invoice\PendingPurchaseCreatedEvent;
use App\Models\Invoice;
use App\Models\ItemSerials;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreatePurchaseRequest extends FormRequest
{
    use TransactionAccounting, PaymentAccounting, IdentityAccounting, ExpensesAccounting, AmountsAccounting;

    private $created_invoice;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create purchase');
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
            'pending_purchase_id' => 'required|integer',
            'methods.*.id' => 'required|integer|exists:accounts,id',
            'items' => 'required|array',
            'items.*.id' => ['required', 'integer', 'exists:items,id'],
            'items.*.price' => 'required|numeric|min:0|',
            'items.*.total' => 'required|numeric',
            'items.*.tax' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'items.*.net' => 'required|numeric',
            'items.*.discount' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric',
            'items.*.price_with_tax' => 'required|numeric|min:0',
            'items.*.serials.*' => ['required', function ($attr, $value, $fail) {
                $serial = ItemSerials::where('serial', $value)->first();
                if (!empty($serial)) {
                    if (in_array($serial->current_status, ['saled', 'available', 'return_sale'])) {
                        $fail('this serial is already exists');
                    }
                }
            }],
            'vendor_invoice_id' => 'required|string',
            'remaining' => 'required|numeric',
            'expenses.*.id' => 'integer|required|exists:expenses',
            'expenses.*.is_open' => 'boolean|required',
            'expenses.*.is_apended_to_net' => 'boolean|required',
        ];
    }

    public function save()
    {
        if ($this->user()->can('confirm purchase')) {
            $result = $this->createActivatedPurchase();
        } else {
            $result = $this->createUnActivatedPurchase();
        }

        if ($this->pending_purchase_id > 0) {
            $invoice = Invoice::find($this->pending_purchase_id);
            if ($invoice)
                $invoice->delete();
        }
        return $result;

    }

    public function createActivatedPurchase()
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::publish(['invoice_type' => 'purchase', 'parent_id' => 0]);
            $purchase = $invoice->publishSubInvoice('purchase', [
                'invoice_type' => 'purchase',
                'prefix' => 'PUI-',
                'vendor_id' => $this->input("vendor_id"),
                'vendor_invoice_id' => $this->input("vendor_invoice_id"),
                'receiver_id' => $this->input("receiver_id")]);
            $expenses = $this->toExtractExpenses();
            $expense_amount = floatval(collect($expenses)->sum('amount'));
            $invoice->add_items_to_invoice($this->items, $purchase, $expenses, 'purchase', $this->input("vendor_id"));
            $this->toGetAndUpdatedAmounts($invoice, $expense_amount);
            $this->toCreateInvoiceTransactions($invoice, $this->items, $this->methods, $expenses);
            DB::commit();
            return $invoice->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function toExtractExpenses()
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

    public function createUnActivatedPurchase()
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::publish(['invoice_type' => 'pending_purchase', 'parent_id' => 0]);
            $purchase = $invoice->publishSubInvoice('purchase', [
                'invoice_type' => 'pending_purchase',
                'prefix' => 'PPU-',
                'vendor_id' => $this->input("vendor_id"),
                'vendor_invoice_id' => $this->input("vendor_invoice_id"),
                'receiver_id' => $this->input("receiver_id")]);
            $expenses = $this->toExtractExpenses();
            $expense_amount = floatval(collect($expenses)->sum('amount'));
            $invoice->add_items_to_invoice($this->items, $purchase, $expenses, 'pending_purchase', $this->input("vendor_id"));
            $this->toGetAndUpdatedAmounts($invoice, $expense_amount);
//				$this->toCreateInvoiceTransactions($invoice,$this->items,$this->methods,$expenses);

            DB::commit();
            event(new PendingPurchaseCreatedEvent($invoice->fresh()));
            return $invoice->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}
