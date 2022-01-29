<?php

namespace App\Http\Requests\Store\ShippingAddress;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StoreShippingAddressRequest extends FormRequest
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
            'city_id' => 'required|integer|exists:cities,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|mobileNumber',
            'area' => 'required|string|max:100',
            'return_object' => 'nullable|boolean'
        ];
    }

    public function store()
    {
        return DB::transaction(function () {
            $loggedUser = auth('client')->user();
            $data = $this->only(
                'city_id',
                'first_name',
                'last_name',
                'phone_number',
                'building_number',
                'street_name',
                'zip_code'
            );
            $data['description'] = $this->input('area') . " " . $this->input('street_name');
            $loggedUser->shippingAddresses()->create($data);
            DB::commit();
            return Inertia::location('/web/cart/shipping_address');
        });
    }
}
