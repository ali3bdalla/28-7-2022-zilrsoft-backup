<?php

namespace App\Http\Requests\Backend\Store\Shipping;

use App\Models\ShippingMethod;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreShippingMethodDeliveryManRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'id_number' => 'required|string',
            'phone_number' => 'required|string',
        ];
    }


    public function store(ShippingMethod $shippingMethod)
    {
        DB::beginTransaction();


        try {


            $data = $this->only('first_name', 'last_name', 'id_number', 'phone_number');
            $data['organization_id'] = $this->user()->organization_id;
            $data['creator_id'] = $this->user()->id;
            $data['hash'] = Str::random(255);
            $shippingMethod->deliveryMen()->create($data);
            DB::commit();

            return back();
        } catch (QueryException $queryException) {
            DB::rollBack();

            throw  $queryException;
        }
    }
}
