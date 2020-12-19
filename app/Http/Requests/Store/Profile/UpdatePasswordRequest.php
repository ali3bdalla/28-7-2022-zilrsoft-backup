<?php

namespace App\Http\Requests\Store\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
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
            'password' => 'required|string|min:8|confirmed',
            'old_password' => 'required|string|min:8',
        ];
    }


    public function update()
    {
        try {
            throw_if(!Hash::check($this->input('old_password'), $this->user()->password), ValidationException::withMessages(['old_password' => 'old password isn\'t valid ']));
            $this->user()->update([
               'password' => Hash::make($this->input('password'))
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }
}
