<?php

namespace App\Http\Requests\Payments;

use AliAbdalla\Tafqeet\Core\Tafqeet;
use App\Models\Account;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StorePaymentRequest extends FormRequest
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
            'description' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric',
            'voucher_type' => 'required|in:transfer,cash,check',
            'org_account_id' => 'required|integer|organization_exists:App\Models\Account,id',
            'user_account_id' => 'nullable|exists:user_gateways,id',
            'payment_type' => 'in:payment,receipt',
        ];
    }

    public function store()
    {
        if ($this->input('voucher_type') == 'transfer' && $this->input('payment_type') == 'payment') {
            if ($this->input('user_account_id') == null) {
                throw ValidationException::withMessages([
                    'user_account_id' => ['required field on transfer '],
                ]);
            }
        }

        DB::beginTransaction();
        try {

            $loggedUser = auth()->user();
            $chartAccount = Account::findOrFail($this->input('org_account_id'));
            $vendorAccounts = Account::where('slug', 'vendors')->first();

            $container = $loggedUser->organization->transactions_containers()->create(
                [
                    'creator_id' => $loggedUser->id,
                    'description' => $this->input('description'),
                    'amount' => $this->input('amount'),
                ]
            );

            $transactionData =
            $user = User::findOrFail($this->input('user_id'));
            if ($this->input('payment_type') == 'payment') {
//                $this->toUpdateVendorBalance($user,'sub',$this->input("amount"));
                $chartAccount->transactions()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'amount' => $this->input('amount'),
                    'user_id' => $this->input('user_id'),
                    'type' => 'credit',
                    'description' => 'vendor_balance',
                    'container_id' => $container->id
                ]);
                $vendorAccounts->transactions()->create([
                    'creator_id' => $loggedUser->id,
                    'organization_id' => $loggedUser->organization_id,
                    'amount' => $this->input('amount'),
                    'user_id' => $user->id,
                    'container_id' => $container->id,
                    'description' => 'vendor_balance',
                    'type' => 'debit',
                ]);


            } else {

//                $this->toUpdateClientBalance($user, 'sub', $this->input("amount"));
                $organization_account->debit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'amount' => $this->amount,
                    'user_id' => $this->user_id,
                    'description' => 'client_balance',
                    'container_id' => $container->id
                ]);

                $client_account = auth()->user()->toGetManagerAccount('clients');
                $client_account->credit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'amount' => $this->amount,
                    'user_id' => $user->id,
                    'container_id' => $container->id,
                    'description' => 'client_balance'
                ]);


            }


            $organization_account->paymentable()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'user_id' => $this->user_id,
                'amount' => $this->amount,
                'slug' => $this->voucher_type,
                'amount_ar_words' => Tafqeet::arablic($this->amount),
                'amount_en_words' => Tafqeet::arablic($this->amount),
                'description' => $this->description,
                'payment_type' => $this->payment_type,
                'user_account_id' => $this->user_account_id,
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }


        return $organization_account;
    }
}
