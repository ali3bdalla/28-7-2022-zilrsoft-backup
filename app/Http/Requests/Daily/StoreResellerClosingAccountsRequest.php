<?php

namespace App\Http\Requests\Daily;

use App\Models\Account;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'gateways.*.amount' => 'required|numeric',
        ];
    }

    public function store()
    {

        DB::beginTransaction();
        try {
            $totalAvailableAmount = collect($this->input('gateways'))->sum('amount');
            if ($totalAvailableAmount > 0) {
                $loggedUser = $this->user();
                $shiftsShortageAccount = Account::where([
                    ['is_system_account', true],
                    ['slug', 'shifts_shortage'],
                ])->first();
                $tempResellerAccount = Account::where([
                    ['is_system_account', true],
                    ['slug', 'temp_reseller_account'],
                ])->first();

                $shouldBeAvailableAmount = $this->getActualAmountFromInvoices($loggedUser);
                
                $container = TransactionsContainer::create([
                    'creator_id' => $loggedUser->id,
                    'description' => 'close_account',
                    'amount' => 0,
                    'organization_id' => $loggedUser->organization_id,
                ]);
                $baseTransactionData = [
                    'creator_id' => $loggedUser->id,
                    'container_id' => $container->id,
                    'description' => 'close_account',
                    'organization_id' => $loggedUser->organization_id,
                ];
                $totalDebitAmount = 0;
                foreach ($this->input("gateways") as $gateway) {
                    if ($gateway['amount'] > 0) {
                        $gatewayData = $baseTransactionData;
                        $gatewayData['account_id'] = $gateway['id'];
                        $gatewayData['amount'] = $this->getGatewayAmountWithoutAfterCuttingTheVouchersAmount($gateway['amount'],$gateway['id'],$loggedUser);
                        $gatewayData['type'] = 'debit';
                        Transaction::create($gatewayData);
                        $totalDebitAmount += (float) $gateway['amount'];
                    }

                }

                $shortShortageAmount = (float) $totalDebitAmount - (float) $shouldBeAvailableAmount;
                $shortSourceAmount = $shortShortageAmount;
                // 1200 , 1000 => 200
                if ($shortShortageAmount != 0) {
                    if ($shortShortageAmount < 0) {
                        // he pay more than he should has
                        $shortShortageAmount = abs($shortShortageAmount);
                        $totalDebitAmount += (float) $shortShortageAmount;
                        $transactionData = $baseTransactionData;
                        $transactionData['account_id'] = $shiftsShortageAccount->id;
                        $transactionData['amount'] = (float) $shortShortageAmount;
                        $transactionData['type'] = 'debit';
                        Transaction::create($transactionData);

                    } else {
                        $transactionData = $baseTransactionData;
                        $transactionData['account_id'] = $shiftsShortageAccount->id;
                        $transactionData['amount'] = (float) $shortShortageAmount;
                        $transactionData['type'] = 'credit';
                        Transaction::create($transactionData);
                    }
                }

                $transactionData = $baseTransactionData;
                $transactionData['account_id'] = $tempResellerAccount->id;
                $transactionData['amount'] = (float) $shouldBeAvailableAmount;
                $transactionData['type'] = 'credit';
                Transaction::create($transactionData);
                $container->update([
                    'amount' => $shouldBeAvailableAmount,
                ]);
                $loggedUser->resellerClosingAccounts()->create([
                    'organization_id' => $loggedUser->organization_id,
                    'transaction_type' => "close_account",
                    'container_id' => $container->id,
                    'from' => $loggedUser->accounts_closed_at,
                    'to' => now(),
                    'amount' => $shouldBeAvailableAmount,
                    'shortage_amount' => $shortSourceAmount,
                ]);

                $loggedUser->update([
                    'remaining_accounts_balance' => 0,
                    'accounts_closed_at' => now(),
                ]);

            } else {
                throw ValidationException::withMessages([
                    'paid_amount' => 'should be at lest 0.1 ',
                ]);
            }

            DB::commit();

            return $container;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    
    public function getActualAmountFromInvoices($loggedUser)
    {
        $remainingAccountsBalanceAmount = $loggedUser->remaining_accounts_balance;
        $accountsClosedAt = $loggedUser->accounts_closed_at;

        if ($accountsClosedAt != null) {
            $accountsClosedAt = Carbon::parse($accountsClosedAt);
            $inAmount = Payment::where([
                ['creator_id', $loggedUser->id],
            ])->where('created_at', '>=', $accountsClosedAt)->where([
            	['payment_type', 'receipt'],
	            ['invoice_id','!=',null]
            ])->sum('amount');
            $outAmount = Payment::where([
                ['creator_id', $loggedUser->id],
            ])->where('created_at', '>=', $accountsClosedAt)->where([
	            ['payment_type', 'payment'],
	            ['invoice_id','!=',null]
            ])->sum('amount');
        } else {
            $inAmount = Payment::where([
                ['creator_id', $loggedUser->id],
            ])->where('payment_type', 'receipt')->sum('amount');

            $outAmount = Payment::where([
                ['creator_id', $loggedUser->id],
            ])->where('payment_type', 'payment')->sum('amount');
        }

        return $inAmount + $remainingAccountsBalanceAmount - $outAmount;
    }
	
	public function getGatewayAmountWithoutAfterCuttingTheVouchersAmount($amount,$id,$loggedUser)
	{
		$gateway = Account::find($id);
		
		$accountsClosedAt = $loggedUser->accounts_closed_at;
		
		
		
		if ($accountsClosedAt != null) {
			$accountsClosedAt = Carbon::parse($accountsClosedAt);
			$inAmount = Payment::where([
				['creator_id', $loggedUser->id],
				['account_id', $gateway->id],
			])->where('created_at', '>=', $accountsClosedAt)->where([
				['payment_type', 'receipt'],
				['invoice_id','==',null],
				['account_id', $gateway->id],

			])->sum('amount');
//			$outAmount = Payment::where([
//				['creator_id', $loggedUser->id],
//				['account_id', $gateway->id],
//
//			])->where('created_at', '>=', $accountsClosedAt)->where([
//				['payment_type', 'payment'],
//				['invoice_id','=',null],
//				['account_id', $gateway->id],
//
//			])->sum('amount');
		} else {
			$inAmount = Payment::where([
				['creator_id', $loggedUser->id],
				['account_id', $gateway->id],
			
			])->where('payment_type', 'receipt')->sum('amount');
			
//			$outAmount = Payment::where([
//				['creator_id', $loggedUser->id],
//				['account_id', $gateway->id],
//
//			])->where('payment_type', 'payment')->sum('amount');
		}
		
		
		return (float)$amount - $inAmount;
    }
}
