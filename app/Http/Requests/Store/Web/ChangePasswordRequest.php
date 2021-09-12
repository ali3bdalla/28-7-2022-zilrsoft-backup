<?php

namespace App\Http\Requests\Store\Web;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends VerifyVerificationCodeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(),[
            'password' => ['required','string','confirmed','min:6']
        ]);
    }

    public function getPassword()
    {
        return $this->input('password');
    }
}
