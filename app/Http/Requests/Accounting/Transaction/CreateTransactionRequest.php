<?php

namespace App\Http\Requests\Accounting\Transaction;

use App\Models\Account;
use App\Models\Manager;
use App\Models\Entry;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateTransactionRequest extends FormRequest
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
            'transactions' => 'required|array',
            "transactions.*.id" => "required|integer|organization_exists:App\Models\Account,id",
            "transactions.*.credit_amount" => "required|price",
            "transactions.*.debit_amount" => "required|price",
            "transactions.*.is_credit" => "required|boolean",
            "transactions.*.vendor_id" => ["integer", "exists:users,id"],
            "transactions.*.client_id" => ["integer", "exists:users,id"],
            "transactions.*.item_id" => ["integer", "organization_exists:App\Models\Item,id"],
            'description' => "required|string",
            'amount' => "required|numeric",
        ];
    }

    public function save()
    {

        if ($this->validateTransactionEntities()) {
            DB::beginTransaction();
            try {

                $container = $this->loggedUser()->organization->transactions_containers()->create(
                    [
                        'creator_id' => $this->loggedUser()->id, 'description' => $this->input("description"), 'amount' => $this->input("amount"),
                    ]
                );
                foreach ($this->input("transactions") as $index => $account_json) {
                    $account = Account::find($account_json['id']);
                    if ($account->_isStock()) {
                        $this->_validateMissingRules('item_id', $index);
                        $this->toCreateStockTransaction($account_json, $account, $container);
                    } elseif ($account->_isClients()) {
                        $this->_validateMissingRules('client_id', $index);
                        $this->toCreateClientTransaction($account_json, $account, $container);
                    } elseif ($account->_isVendors()) {
                        $this->_validateMissingRules('vendor_id', $index);
                        $this->toCreateVendorTransaction($account_json, $account, $container);
                    } else {
                        $this->toCreateAccountTransaction($account_json, $account, $container);
                    }
                }
                DB::commit();
                return response($container->fresh()->load('transactions'), 200);
            } catch (ValidationException $exception) {
                DB::rollBack();
                return response($exception->errors(), 422);
            } catch (Exception $exception) {
                DB::rollBack();
                return response($exception->getMessage(), 500);
            }
        } else {
            return response(["message" => "Credit Amount Should Equal Debit Amount"], 400);
        }
    }

    private function validateTransactionEntities(): bool
    {
        $total_credit = 0;
        $total_debit = 0;
        foreach ($this->input("transactions") as $transaction) {
            if ($transaction['is_credit'])
                $total_credit = $total_credit + floatval($transaction['credit_amount']);
            else
                $total_debit = $total_debit + floatval($transaction['debit_amount']);
        }

        return $total_credit == $total_debit || $total_credit != $this->input('amount');
    }

    public function loggedUser(): Manager
    {
        return Auth::user();
    }

    /**
     * @throws ValidationException
     */
    private function _validateMissingRules($validator = 'item_id', $index)
    {
        if (!collect($this->input('transactions')[$index])->has($validator))
            throw  new ValidationException($validator, 'is required');
    }

    private function toCreateStockTransaction($requestData, Account $account, Entry $container)
    {

    }

    private function toCreateClientTransaction($requestData, Account $account, Entry $container)
    {
        $data = [];
        $data['creator_id'] = $this->loggedUser()->id;
        $data['organization_id'] = $this->loggedUser()->organization_id;
        if ($requestData['is_credit']) {
            $data['creditable_id'] = $account->id;
            $data['creditable_type'] = Account::class;
            $data['amount'] = $requestData['credit_amount'];
        } else {

            $data['debitable_id'] = $account->id;
            $data['debitable_type'] = Account::class;
            $data['amount'] = $requestData['debit_amount'];
        }
        $data['user_id'] = $requestData['client_id'];
        $data['description'] = "client_balance";
        $container->transactions()->create($data);
    }

    private function toCreateVendorTransaction($requestData, Account $account, Entry $container)
    {

        $vendor = User::find($requestData['vendor_id']);
        $data = [];
        $data['creator_id'] = $this->loggedUser()->id;
        $data['organization_id'] = $this->loggedUser()->organization_id;
        if ($requestData['is_credit']) {
            $data['creditable_id'] = $account->id;
            $data['creditable_type'] = Account::class;
            $data['amount'] = $requestData['credit_amount'];
            $operator = 'add';
        } else {

            $data['debitable_id'] = $account->id;
            $data['debitable_type'] = Account::class;
            $data['amount'] = $requestData['debit_amount'];
            $operator = 'sub';
        }

        $data['user_id'] = $requestData['vendor_id'];
        $data['description'] = "vendor_balance";
        $container->transactions()->create($data);
    }

    private function toCreateAccountTransaction($requestData, Account $account, Entry $container)
    {
        $data = [];
        $data['creator_id'] = $this->user()->id;
        $data['organization_id'] = $this->user()->organization_id;
        if ($requestData['is_credit']) {
            $data['creditable_id'] = $account->id;
            $data['creditable_type'] = Account::class;
            $data['amount'] = $requestData['credit_amount'];
        } else {
            $data['debitable_id'] = $account->id;
            $data['debitable_type'] = Account::class;
            $data['amount'] = $requestData['debit_amount'];
        }
        $container->transactions()->create($data);

    }

}
