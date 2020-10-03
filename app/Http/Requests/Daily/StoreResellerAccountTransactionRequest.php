<?php

namespace App\Http\Requests\Daily;

use App\Models\Account;
use App\Models\TransactionsContainer;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreResellerAccountTransactionRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'gateway_id' => 'required|integer|exists:accounts,id',
            'receiver_id' => 'required|integer|exists:managers,id',
            'receiver_gateway_id' => 'required|integer|exists:accounts,id',
        ];
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $loggedUser = auth()->user();
            $tempResellerAccount = Account::where([['is_system_account', true], ['slug', 'temp_reseller_account']])->first();
            $sourceGateway = Account::find($this->input('gateway_id'));
            $sourceGatewayAmount = (float) $sourceGateway->getSingleAccountBalance(); //5000

            // 4000
            $sourceGatewayRemainingAmount = $sourceGatewayAmount - (float) $this->input('amount'); //1000

            $container = TransactionsContainer::create(
                [
                    'creator_id' => $loggedUser->id,
                    'description' => 'transfer_amount',
                    'amount' => (float) $this->input('amount'),
                    'organization_id' => $loggedUser->organization_id,
                    'is_pending' => true,
                ]
            );

            $transactionData = [
                'container_id' => $container->id,
                'is_pending' => true,
                'description' => 'transfer_amount',
                'creator_id' => $loggedUser->id,
                'organization_id' => $loggedUser->organization_id,
            ];

            // // add manager private transactions
            $createdTransaction = $loggedUser->resellerClosingAccounts()->create([
                'organization_id' => $loggedUser->organization_id,
                'transaction_type' => "transfer",
                'container_id' => $container->id,
                'receiver_id' => $this->input('receiver_id'),
                'amount' => $this->input('amount'),
                'remaining_accounts_balance' => $sourceGatewayRemainingAmount,
                'is_pending' => true,
            ]);

            //
            /**
             * ==========================================
             *
             * ==========================================
             */
            $data = $transactionData;
            $data['account_id'] = (int) $this->input('receiver_gateway_id');
            $data['type'] = 'debit';
            $data['amount'] = $this->input('amount'); //4000
            $container->transactions()->create($data);

            /**
             * ==========================================
             *
             * ==========================================
             */

            $data = $transactionData;
            $data['account_id'] = $sourceGateway->id;
            $data['type'] = 'credit';
            $data['amount'] = abs($sourceGatewayAmount); //5000
            $container->transactions()->create($data);

            if ($sourceGatewayRemainingAmount > 0) {
                $data = $transactionData;
                $data['account_id'] = $tempResellerAccount->id;
                $data['type'] = 'debit';
                $data['amount'] = $sourceGatewayRemainingAmount;
                $container->transactions()->create($data);
            } else {
                $data = $transactionData;
                $data['account_id'] = $tempResellerAccount->id;
                $data['type'] = 'credit';
                $data['amount'] = abs($sourceGatewayRemainingAmount);
                $container->transactions()->create($data);
            }
            DB::commit();

            return $createdTransaction;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}
