<?php

namespace App\Http\Requests\Order;

use App\Models\Item;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "items" => "required|array",
            "items.*.id" => ["required", "integer", Rule::exists('items', 'id')],
            "items.*.quantity" => ["required", "numeric", "min:1"],
            'shipping_method_id' => ['nullable', Rule::exists('shipping_methods', 'id')],
            'shipping_address_id' => ['nullable', Rule::exists('shipping_addresses', 'id')],
            'payment_method_id' => ['nullable'],
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getShippingAddress(): ?ShippingAddress
    {
        return ShippingAddress::find($this->input('shipping_address_id'));
    }

    public function getShippingMethod(): ?ShippingMethod
    {
        return ShippingMethod::find($this->input('shipping_method_id'));
    }

    public function getPaymentMethodId()
    {
        return $this->input('payment_method_id');
    }

    /**
     * @throws ValidationException
     */
    public function ensureQuantitiesAreValid()
    {
        foreach ($this->getItems() as $item) {
            if (!(Item::findOrFail($item['id']))->isAvailableQuantityCanHandle((float)$item['quantity']))
                throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
        }
    }

    public function getItems()
    {
        return $this->input('items', []);
    }
}
