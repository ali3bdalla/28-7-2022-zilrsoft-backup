<?php

namespace App\Http\Requests;

use App\Gateway;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentMethodForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAuthorizedTo('manage-methods');
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
            'name'=>'required|string|unique:pay_ways,name',
            'ar_name'=>'required|string|unique:pay_ways,ar_name',
            'parent_id'=>'required|integer'
        ];
    }

    public  function  save(){
        $data = $this->only('name','ar_name','parent_id');
        $data['creator_id'] = $this->user()->id;
        $data['is_default'] = false;


        $way = $this->user()->organization->payments_ways()->create($data);

        if($this->has('is_default') && $this->filled('is_default'))
        {

            $way->setAsDefaultMethod();

        }
        return back();
        return redirect(route('management.payments_ways.index'));
    }
}
