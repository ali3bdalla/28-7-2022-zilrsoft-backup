<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoruForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return $this->user()->isAuthorizedTo('update_category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name'=>"required|min:3|string",
            'ar_name'=>"required|min:3|string",
            'description'=>"required|min:3|string",
            'ar_description'=>"required|min:3|string",
            'parent_id'=>"required|integer",
        ];
    }





}
