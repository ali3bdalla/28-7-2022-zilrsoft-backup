<?php

namespace App\Http\Requests\Accounting\V2;

use App\Invoice;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create sale');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            "notes" => "nullable|string",
            "items" => "required|array",
            "items.*.id" => "required|integer|exists:items,id",
            "items.*.price" => "validate_item_price_or_discount|price",
            "items.*.purchase_price" => "validate_item_purchase_price|price",
            "items.*.discount" => "validate_item_price_or_discount",
            "items.*.qty" => "required|integer|item_has_available_qty:items.*.id",
            "items.*.expense_vendor_id" => "validate_expense_vendor",
            "items.*.serials.*" => 'required|exists:item_serials,serial',
            "items.*.serials" => "array",
            "client_id" => "required|integer|exists:users,id",
            "salesman_id" => "required|integer|exists:managers,id",
            "alice_name" => "nullable|string",
            'methods.*.id' => 'required|integer|exists:accounts,id',
        ];
    }

    public function save()
    {
        DB::beginTransaction();
        try {
            // create invoice
            $invoice = new Invoice();
            $invoice->invoice_type = 'sale';

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

        }
    }


}
