<?php

namespace App\Http\Requests\Store\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class AuthLoginRequest extends FormRequest
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
        return [
            'phone_number' => ['required', 'string'],
            'password' => ['required', "string", 'min:6'],
        ];
    }

    public function getPhoneNumber(): string
    {
        return $this->input('phone_number');
    }

    public function getPassword()
    {
        return $this->input('password');
    }
}
