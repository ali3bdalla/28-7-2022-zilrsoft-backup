<?php

namespace App\Http\Requests\API;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class AcceptOrderRequest extends FormRequest
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
            'account_id' => 'required|integer|exists:accounts,id'
        ];
    }

    public function getTargetAccount(): Account
    {
        return Account::find($this->input('account_id'));
    }
}
