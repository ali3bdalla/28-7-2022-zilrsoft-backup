<?php

namespace App\Http\Requests\Backend\Store\Shipping;

use App\Models\ShippingMethod;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateShippingMethodRequest extends FormRequest
{

    use ShippingValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function update(ShippingMethod $shippingMethod)
    {
        DB::beginTransaction();

        try {


            $data = $this->only('name','item_id', 'ar_name', 'max_base_weight', 'max_base_weight_cost', 'max_base_weight_price', 'kg_after_max_weight_cost', 'kg_rate_after_max_price');
            if($this->hasFile('logo'))
            {
                $imageurl  = Storage::url($this->file('logo')->store('images/shipping_methods', 'public'));
                $data['logo'] = $imageurl;

            }
            $shippingMethod->update($data);


            if ($this->has('cities'))
            {
                $shippingMethod->cities()->delete();
                foreach ($this->input('cities') as $cityId)
                    $shippingMethod->cities()->create(['city_id' => $cityId]);
            }


            DB::commit();

            return redirect(route('store.shipping.index'));
        } catch (QueryException $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }
}
