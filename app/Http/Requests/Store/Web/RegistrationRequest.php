<?php

namespace App\Http\Requests\Store\Web;

use App\Repository\UserRepositoryContract;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegistrationRequest extends AuthLoginRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
        ]);
    }

    public function getCountryCode(): string
    {
        return $this->getPhoneNumber()->getCountry();
    }

    /**
     * @throws Throwable
     */
    public function ensureIsValidPhoneNumber(UserRepositoryContract $userRepositoryContract)
    {
        $user = $userRepositoryContract->getVerifiedOnlineUser($this->getPhoneNumber());
        throw_if(!$this->getPhoneNumber() || $user, ValidationException::withMessages([
            'phone_number' => 'should be unique'
        ]));
    }

    public function getLastName()
    {
        return $this->input('last_name');
    }

    public function getFirstName()
    {
        return $this->input('first_name');
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
