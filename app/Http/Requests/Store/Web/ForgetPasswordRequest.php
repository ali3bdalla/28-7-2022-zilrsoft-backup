<?php

namespace App\Http\Requests\Store\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class ForgetPasswordRequest extends FormRequest
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
            'phone_number' => ['required', 'string', Rule::exists('users','phone_number')->whereNotNull('phone_number_verified_at')->where('user_slug','online_user')],
        ];
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return PhoneNumber::make($this->input('phone_number'))->ofCountry("SA");
    }

}
