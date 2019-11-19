<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
	        
	        'name' => 'required|string|unique:accounts',
	        'ar_name' => 'required|string|unique:accounts',
	        'parent_id' => 'required|integer|exists:accounts,id'
        ];
    }
}
