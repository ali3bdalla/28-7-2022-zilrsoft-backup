<?php

namespace App\Http\Requests\Vouchers;

use App\Enums\VoucherTypeEnum;
use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\Entry;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreVoucherRequest extends FormRequest
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
            'description' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric',
            'voucher_type' => 'required|in:transfer,cash,check',
            'org_account_id' => 'required|integer|exists:accounts,id',
            'payment_type' => 'in:payment,receipt',
            'user_account_id' => [Rule::requiredIf(function() {
                return $this->input('voucher_type') == 'transfer' && $this->input('payment_type') == 'payment';
            }),"integer","exists:user_gateways,id"]
        ];
    }



    public function getUserAccount(): Account
    {
        if ($this->input('payment_type') == 'payment') Account::getSystemAccount("vendors");
        return Account::getSystemAccount("clients");
    }
    public function getOrganizationAccount(): Account
    {
        return Account::findOrFail($this->input("org_account_id"));
    }
    public function getUser(): User
    {
        return User::find($this->input('user_id'));
    }

    public function getAmount(): float
    {
        return  (float)$this->input('amount');
    }

    public function getType(): VoucherTypeEnum
    {
        return VoucherTypeEnum::from($this->input('payment_type'));
    }
    public function getDescription(): ?string
    {
        return $this->input('description');
    }

}
