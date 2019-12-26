<?php

namespace App\Http\Requests\Accounting\Expense;

use Illuminate\Foundation\Http\FormRequest;

class CreateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manage expenses');
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
	        'name'=>'required|string|unique:expenses,name',
	        'ar_name'=>'required|string|unique:expenses,ar_name',
        ];
    }
	
	
    
}
