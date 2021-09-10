<?php

namespace App\Http\Requests\Account;

use App\Models\Account;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class StoreAccountRequest extends FormRequest
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
            //
            'name' => 'required|string|organization_unique:App\Models\Account,name',
            'ar_name' => 'required|string|organization_unique:App\Models\Account,name',
            'parent_id' => 'required|integer|organization_exists:App\Models\Account,id',
            'account_type' => 'required|in:credit,debit'
        ];
    }

    public function store(): Redirector|Application|RedirectResponse
    {
        $parent = Account::find($this->input("parent_id"));

        $data = $this->only('parent_id', 'name', 'ar_name');
        $data['organization_id'] = Auth::user()->organization_id;
        $data['type'] = $this->input("account_type");
        $data['slug'] = $parent->slug;
        $data['creator_id'] = Auth::id();

        if ($this->has('is_gateway') && $this->filled('is_gateway'))
            $data['is_gateway'] = true;
        else
            $data['is_gateway'] = false;


        Account::create($data);

        return redirect('/accounts');
    }
}
