<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesRequest extends FormRequest
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
            "items.*.purchase_price" => "validate_item_purchase_price|price",
            "items.*.discount" => "validate_item_price_or_discount",
            "items.*.qty" => "required|integer|item_has_available_qty:items.*.id",
            "items.*.expense_vendor_id" => "validate_expense_vendor",
            "items.*.serials.*" => 'required|validate_item_serials',
            "items.*.serials" => "validate_serials_array|array",
            "client_id" => "required|integer|exists:users,id",
            "salesman_id" => "required|integer|exists:managers,id",
            'methods.*.id' => 'required|integer|exists:accounts,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
