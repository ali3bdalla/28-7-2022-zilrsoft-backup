<?php

namespace App\Http\Requests\Store\Profile;

use App\Models\OnlineUserPlaceholder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdatePhoneNumberRequest extends FormRequest
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
            'phone_number' => 'required|mobileNumber|unique:users,phone_number',
            'otp' => 'nullable|integer'
        ];
    }

    public function change()
    {
        try {

            if ($this->has('otp') && $this->filled('otp')) {

                $this->preformChange();

            } else {
                $this->send();
            }

        } catch (ValidationException $e) {
            throw $e;
        }
    }

    private function preformChange()
    {
        $oldRecord = OnlineUserPlaceholder::where([['phone_number', $this->input('phone_number')], ['otp' , $this->input('otp')]])->first();

        if ($oldRecord) {
            $this->user()->update([
                'phone_number' => $this->input('phone_number')
            ]);
        } else {
            throw ValidationException::withMessages(['otp' => 'invalid otp']);
        }
    }

    private function send()
    {
        $otp = generateOtp();
        $oldRecord = OnlineUserPlaceholder::where('phone_number', $this->input('phone_number'))->first();
        if ($oldRecord) {
            $oldRecord->update([
                'otp' => $otp
            ]);
        } else {
            OnlineUserPlaceholder::create(
                [

                    'phone_number' => $this->input('phone_number'),
                    'first_name' => "",
                    'last_name' => "",
                    'password' => "",
                    'otp' => $otp

                ]
            );
        }

        sendOtp($this->input('phone_number'), $otp);

    }
}
