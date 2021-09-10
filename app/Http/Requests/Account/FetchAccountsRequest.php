<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class FetchAccountsRequest extends FormRequest
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
            'parent_id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string'],
        ];
    }

    public function getName()
    {
        return $this->input('name');
    }

    public function getParentId()
    {
        return $this->input('parent_id');
    }
}
