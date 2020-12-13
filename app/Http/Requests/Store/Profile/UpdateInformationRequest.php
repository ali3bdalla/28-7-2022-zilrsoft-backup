<?php

namespace App\Http\Requests\Store\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInformationRequest extends FormRequest
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
            'email_address' => 'required|email',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
        ];
    }


    public function update()
    {
        $updateData = $this->only('email_address', 'first_name', 'last_name');
        $updateData['name'] = $this->input('first_name') . ' ' . $this->input('last_name');
        $this->user()->update($updateData);
    }
}
