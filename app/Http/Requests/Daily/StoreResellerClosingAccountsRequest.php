<?php

namespace App\Http\Requests\Daily;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreResellerClosingAccountsRequest extends FormRequest
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
            'gateways' => 'required|array',
            'gateways.*.id' => 'required|integer|exists:accounts,id',
            'gateways.*.amount' => 'required|numeric|min:0',
        ];
    }

    public function getBanks()
    {
        return $this->input('gateways');
    }

}
