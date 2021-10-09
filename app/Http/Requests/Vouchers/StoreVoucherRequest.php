<?php

namespace App\Http\Requests\Vouchers;

use App\Enums\VoucherTypeEnum;
use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\TransactionsContainer;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
            'user_account_id' => 'nullable|exists:user_gateways,id',
            'payment_type' => 'in:payment,receipt',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function ensureUserAccountExists()
    {
        if ($this->input('voucher_type') == 'transfer' && $this->input('payment_type') == 'payment') {
            if ($this->input('user_account_id') == null) {
                throw ValidationException::withMessages(
                    [
                        'user_account_id' => ['identity should have bank account'],
                    ]
                );
            }
        }
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
//
//    public function store()
//    {
//
//
//        DB::beginTransaction();
//        try {
//
//            $vendorAccount = Account::where('slug', "vendors")->first();
//            $clientAccount = Account::where('slug', 'clients')->first();
//            $loggedUser = $this->user();
//            $organizationAccount = Account::findOrFail($this->input('org_account_id'));
//            $container = TransactionsContainer::create(
//                [
//                    'creator_id' => $loggedUser->id,
//                    'description' => $this->input('description'),
//                    'amount' => (float)$this->input('amount'),
//                    'organization_id' => $loggedUser->organization_id,
//                ]
//            );
//
//            if ($this->input('payment_type') == 'payment') {
//                $organizationAccount->transactions()->create(
//                    [
//                        'creator_id' => $loggedUser->id,
//                        'organization_id' => $loggedUser->organization_id,
//                        'amount' => (float)$this->input('amount'),
//                        'user_id' => $this->input('user_id'),
//                        'description' => 'vendor_balance',
//                        'container_id' => $container->id,
//                        'type' => 'credit',
//                    ]
//                );
//                $vendorAccount->transactions()->create(
//                    [
//                        'creator_id' => $loggedUser->id,
//                        'organization_id' => $loggedUser->organization_id,
//                        'amount' => (float)$this->input('amount'),
//                        'user_id' => $user->id,
//                        'container_id' => $container->id,
//                        'description' => 'vendor_balance',
//                        'type' => 'debit',
//                    ]
//                );
//                dispatch_sync(new UpdateVendorBalanceJob($user, (float)$this->input('amount'), 'decrease'));
//            } else {
//                $organizationAccount->transactions()->create(
//                    [
//                        'creator_id' => $loggedUser->id,
//                        'organization_id' => $loggedUser->organization_id,
//                        'amount' => (float)$this->input('amount'),
//                        'user_id' => $this->user_id,
//                        'description' => 'client_balance',
//                        'container_id' => $container->id,
//                        'type' => 'debit',
//
//                    ]
//                );
//
//                $clientAccount->transactions()->create(
//                    [
//                        'creator_id' => $loggedUser->id,
//                        'organization_id' => $loggedUser->organization_id,
//                        'amount' => (float)$this->input('amount'),
//                        'user_id' => $user->id,
//                        'container_id' => $container->id,
//                        'description' => 'client_balance',
//                        'type' => 'credit',
//
//                    ]
//                );
//                dispatch_sync(new UpdateClientBalanceJob($user, (float)$this->input('amount'), 'decrease'));
//
//            }
//
//            $organizationAccount->payments()->create(
//                [
//                    'creator_id' => $loggedUser->id,
//                    'organization_id' => $loggedUser->organization_id,
//                    'user_id' => $this->input('user_id'),
//                    'amount' => $this->input('amount'),
//                    'slug' => $this->input('voucher_type'),
//                    'description' => $this->input('description'),
//                    'payment_type' => $this->input('payment_type'),
//                    'user_account_id' => $this->input('user_account_id'),
//                ]
//            );
//
//            DB::commit();
//        } catch (Exception $exception) {
//            DB::rollBack();
//            throw $exception;
//        }
//
//        // return $organization_account;
//    }
}
