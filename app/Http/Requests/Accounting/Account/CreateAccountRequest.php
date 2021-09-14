<?php

namespace App\Http\Requests\Accounting\Account;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create chart');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:accounts',
            'ar_name' => 'required|string|unique:accounts',
            'parent_id' => 'required|integer|exists:accounts,id',
            'account_type' => 'required|in:credit,debit'
        ];
    }

    public function save()
    {
        $parent = Account::find($this->parent_id);

        $data = $this->only('parent_id', 'name', 'ar_name');
        $data['organization_id'] = auth()->user()->organization_id;
        $data['serial'] = auth()->user()->organization_id;
        $data['type'] = $this->account_type;
        $data['slug'] = $parent->slug;

        if ($this->has('is_gateway') && $this->filled('is_gateway'))
            $data['is_gateway'] = true;
        else
            $data['is_gateway'] = false;


        auth()->user()->accounts()->create($data);
    }
}
