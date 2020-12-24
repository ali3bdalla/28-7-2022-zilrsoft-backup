<?php

namespace App\Http\Requests\Store\ShippingAddress;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
            //
            'city_id' => 'required|integer|exists:cities,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|mobileNumber',
            'description' => 'required|string|max:100',
        ];
    }

    public function store()
    {
        DB::beginTransaction();


        try {
            $loggedUser = auth('client')->user();

            $shippingAddress = $loggedUser->shippingAddresses()->create(
                $this->only('city_id', 'first_name', 'last_name', 'phone_number', 'building_number', 'description',
                    'street_name', 'zip_code')
            );
            DB::commit();
            return $shippingAddress;
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw  $queryException;
        }
    }
}