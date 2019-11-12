<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFilterValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
        public function authorize()
    {
        return $this->user()->isAuthorize('manage-filter');
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
            'name'=>'required|string|unique:filter_values,name',
            'filter_id'=>'required|integer|exists:filters,id',
            'ar_name'=>'required|string|unique:filter_values,ar_name'

        ];
    }


    public function save(){
        return auth()->user()->filters_values()->create($this->all());
    }
}
