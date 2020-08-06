<?php

namespace Modules\Sales\Http\Requests;

use App\Invoice;
use App\TransactionsContainer;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Jobs\CreateSalesEntityTransactionsJob;
use Modules\Expenses\Jobs\CreateExpensesPrePurchasesJob;
use Modules\Sales\Jobs\CreateSalesItemsJob;
use Modules\Sales\Jobs\DeleteQuotationAfterSubSalesCreatedJob;
use Modules\Sales\Jobs\EnsureSalesDataAreCorrectJob;
use Modules\Sales\Jobs\UpdateInvoiceTotalsJob;

class CreateSalesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "items" => "required|array",
            "items.*.id" => "required|integer|exists:items,id",
            "items.*.price" => "validate_item_price_or_discount|price",
            "items.*.purchase_price" => "purchaseItemPrice|price",
            "items.*.discount" => "validate_item_price_or_discount",
            "items.*.qty" => "required|integer|salesItemQty",
            "items.*.expense_vendor_id" => "validate_expense_vendor",
            "items.*.serials.*" => 'required|validate_item_serials',
            "items.*.serials" => "validate_serials_array|array",
            "client_id" => "required|integer|exists:users,id",
            "salesman_id" => "required|integer|exists:managers,id",
            'methods.*.id' => 'required|integer|exists:accounts,id',
            'methods.*.amount' => 'required|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create sale');
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $authUser = auth()->user();
            dispatch(new CreateExpensesPrePurchasesJob($this->input('items')));
            $invoice = Invoice::create([
                'invoice_type' => 'sale',
                'notes' => $this->has('notes') ? $this->input('notes') : "",
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'parent_invoice_id' => $this->input('parent_id') == null ? 0 : $this->input('parent_id'),
                'is_deleted' => $this->has('is_deleted') ? $this->input('is_deleted') : 0
            ]);
            $invoice->sale()->create([
                'salesman_id' => $authUser->id,
                'client_id' => $this->input('client_id'),
                'organization_id' => $authUser->organization_id,
                'invoice_type' => 'sale',
                'alice_name' => $this->input('alice_name'),
                "prefix" => "SAI-"
            ]);
            $transactionContaniner = new TransactionsContainer(
                [
                    'creator_id' => $this->user()->id,
                    'organization_id' => $this->user()->organization_id,
                    'invoice_id' => $invoice->id,
                    'amount' => 0,
                    'description' => 'invoice'
                ]
            );
            $transactionContaniner->save();
            dispatch(new CreateSalesItemsJob($transactionContaniner, $invoice, $this->input('items')));
            dispatch(new UpdateInvoiceTotalsJob($invoice));
            dispatch(new CreateSalesEntityTransactionsJob($transactionContaniner, $invoice, $this->input("methods")));
            dispatch(new DeleteQuotationAfterSubSalesCreatedJob($this->input('quotation_id')));
            dispatch(new EnsureSalesDataAreCorrectJob($invoice));
            DB::commit();
            return response($invoice, 200);
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (ValidationException $validationException) {
            DB::rollBack();
            throw $validationException;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;

        }
    }
}
