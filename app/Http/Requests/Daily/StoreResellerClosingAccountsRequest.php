<?php

namespace App\Http\Requests\Daily;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreResellerClosingAccountsRequest extends FormRequest
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
            'gateways' => 'required|array',
            'gateways.*.id' => 'required|integer|exists:accounts,id',
//				'remaining_amount_account_id' => 'required|integer|exists:accounts,id',
            'gateways.*.amount' => 'required|price',
            'period_sales_amount' => 'required|price',
            'remaining_amount' => 'nullable|price',
        ];
    }

    public function store()
    {

        DB::beginTransaction();
        try {


            $loggedUser = $this->user();
            $tempResellerAccount = Account::where([
                ['is_system_account', true],
                ['slug', 'temp_reseller_account'],
            ])->first();


            $container = TransactionsContainer::create([
                'creator_id' => $loggedUser->id,
                'description' => 'account_close',
                'amount' => 0,
                'organization_id' => $loggedUser->organization_id
            ]);

            $baseTransactionData = [
                'creator_id' => $loggedUser->id,
                'container_id' => $container->id,
                'description' => 'close_account',
                'organization_id' => $loggedUser->organization_id
            ];

            $totalDebitAmount = 0;
            foreach ($this->input("gateways") as $gateway) {
                if ($gateway['amount'] > 0) {
                    $gatewayData = $baseTransactionData;
                    $gatewayData['account_id'] = $gateway['id'];
                    $gatewayData['amount'] = (float)$gateway['amount'];
                    $gatewayData['type'] = 'debit';
                    Transaction::create($gatewayData);
                    $totalDebitAmount += (float)$gateway['amount'];
                }

            }

            $shortShortageAmount = $this->getShortageAmount();
            $shiftsShortageAccount = Account::where([
                ['is_system_account', true],
                ['slug', 'shifts_shortage'],
            ])->first();
            if ($shortShortageAmount < 0) {
                $shortShortageAmount = abs($shortShortageAmount);
                $totalDebitAmount += (float)$shortShortageAmount;
                $transactionData = $baseTransactionData;
                $transactionData['account_id'] = $shiftsShortageAccount->id;
                $transactionData['amount'] = (float)$shortShortageAmount;
                $transactionData['type'] = 'debit';
                Transaction::create($transactionData);

            } else {
                $transactionData = $baseTransactionData;
                $transactionData['account_id'] = $shiftsShortageAccount->id;
                $transactionData['amount'] = (float)$shortShortageAmount;
                $transactionData['type'] = 'credit';
                Transaction::create($transactionData);
            }


            $transactionData = $baseTransactionData;
            $transactionData['account_id'] = $tempResellerAccount->id;
            $transactionData['amount'] = (float)$this->input("period_sales_amount");
            $transactionData['type'] = 'credit';
            Transaction::create($transactionData);

            $container->update([
                'amount' => $totalDebitAmount
            ]);
            $lastDate = Carbon::now()->subDays(5);
            $loggedUser->resellerClosingAccounts()->create([
                'organization_id' => $loggedUser->organization_id,
                'transaction_type' => "close_account",
                'transaction_container_id' => $container,
                'close_account_start_date' => $lastDate,
                'close_account_end_date' => now(),
                'amount' => $totalDebitAmount,
                'shortage_amount' => $shiftsShortageAccount,
            ]);

//            $this->toCreateManagerCloseAccountTransaction($debit_total, $container->id, $short_shortage_amount);
//			if ($this->filled('remaining_amount') && $this->has('remaining_amount') && $this->input("remaining_amount") > 0 && $this->filled('remaining_amount_account_id')
//			&& $this->input('remaining_amount_account_id') >= 0){
//				$this->makeReminingCashAmountTransactions($temp_reseller_account);
//			}
//			}


            DB::commit();

            return $container;
        } catch (Exception $exception) {
            DB::rollBack();;
            throw $exception;
        }


    }

    /**
     * @return int|mixed
     */
    public function getShortageAmount()
    {
        $gatewaysAmount = 0;
        foreach ($this->input("gateways") as $gateway) {
            $gatewaysAmount = $gatewaysAmount + $gateway['amount'];
        }
        return $gatewaysAmount - $this->input("period_sales_amount");
    }
}
