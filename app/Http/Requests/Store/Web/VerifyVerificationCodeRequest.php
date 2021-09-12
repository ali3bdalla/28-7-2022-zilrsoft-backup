<?php

namespace App\Http\Requests\Store\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

class VerifyVerificationCodeRequest extends FormRequest
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
            'id' => ['required', 'integer'],
            'verification_code' => ['required'],
            'signature' => ['required', 'string'],
            'expires' => ['required'],
        ];
    }

    /**
     * @throws Throwable
     */
    public function ensureIsValidUrl()
    {
        throw_if(!URL::hasValidSignature($this), ValidationException::withMessages(['url' => 'invalid url']));
    }
    public function getId()
    {
        return $this->input('id');
    }
    public function getVerificationCode()
    {
        return $this->input('verification_code');
    }
}
