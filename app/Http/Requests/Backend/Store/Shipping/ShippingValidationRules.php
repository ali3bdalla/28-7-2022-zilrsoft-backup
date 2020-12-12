<?php

namespace App\Http\Requests\Backend\Store\Shipping;


trait ShippingValidationRules
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'item_id' => 'required|integer|organization_exists:App\Models\Item,id',
            'cities' => 'array',
            'cities.*' => 'required|integer|exists:cities,id',
            'name' => 'required|string',
            'ar_name' => 'required|string',
            'max_base_weight' => 'required|integer|min:0',
            'max_base_weight_cost' => 'required|numeric|min:0',
            'max_base_weight_price' => 'required|numeric|min:0',
            'kg_after_max_weight_cost' => 'required|numeric|min:0',
            'kg_rate_after_max_price' => 'required|numeric|min:0',
        ];
    }
}
