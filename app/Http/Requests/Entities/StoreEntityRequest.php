<?php
	
	namespace App\Http\Requests\Entities;
	
	use App\Jobs\User\Balance\UpdateClientBalanceJob;
	use App\Jobs\User\Balance\UpdateVendorBalanceJob;
	use App\Models\Account;
	use App\Models\TransactionsContainer;
	use App\Models\User;
	use Carbon\Carbon;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\ValidationException;
	
	class StoreEntityRequest extends FormRequest
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
				'transactions' => 'required|array',
				"transactions.*.id" => "required|integer|organization_exists:App\Models\Account,id",
				"transactions.*.amount" => "required|price",
				"transactions.*.type" => "required|in:credit,debit",
				"transactions.*.user_id" => ['nullable', "integer", "exists:users,id"],
				"transactions.*.item_id" => ["nullable", "integer"],
				'description' => "required|string",
				'created_at' => 'nullable',
			];
		}
		
		public function store()
		{
			
			DB::beginTransaction();
			try {
				if($this->created_at != null) {
					$createdAt = Carbon::parse($this->created_at);
					
				} else {
					$createdAt = Carbon::now();
				}
				$loggedUser = $this->user();
				$amount = $this->validateTransactions();
				
				$entity = new TransactionsContainer(
					[
						'organization_id' => $loggedUser->organization_id,
						'creator_id' => $loggedUser->id,
						'description' => $this->input("description"),
						'amount' => $amount,
						'created_at' => $createdAt,
					
					]
				);
				$entity->save();
				
				$transactionInitData = [
					'creator_id' => $loggedUser->id,
					'organization_id' => $loggedUser->organization_id,
				];
				foreach($this->input("transactions") as $transaction) {
					$account = Account::find($transaction['id']);
					$transactionData = $transactionInitData;
					$transactionData['amount'] = $transaction['amount'];
					$transactionData['type'] = $transaction['type'];
					$transactionData['description'] = $transaction['description'];
					$transactionData['is_manual'] = true;
					$transactionData['account_id'] = $account->id;
					$transactionData['created_at'] = $createdAt;
					
					if($account->slug == 'stock') {
						$transactionData['item_id'] = $transaction['item_id'];
					}
					if($account->slug == 'clients') {
						$transactionData['user_id'] = $transaction['user_id'];
						$this->updateUserBalance('client', $transactionData, $account);
					}
					if($account->slug == 'vendors') {
						$transactionData['user_id'] = $transaction['user_id'];
						$this->updateUserBalance('vendor', $transactionData, $account);
					}
					
					$entity->transactions()->create($transactionData);
				}
				
				DB::commit();
				return $entity;
			} catch(ValidationException $exception) {
				DB::rollBack();
				return response($exception->errors(), 422);
			} catch(Exception $exception) {
				DB::rollBack();
				return response($exception->getMessage(), 500);
			}
		}
		
		private function validateTransactions()
		{
			$creditAmount = 0;
			$debitAmount = 0;
			
			foreach($this->input("transactions") as $transaction) {
				if($transaction['type'] == 'credit') {
					$creditAmount += (float)($transaction['amount']);
				} else {
					$debitAmount += (float)($transaction['amount']);
				}
				
			}
			
			$variation = $creditAmount - $debitAmount;
			if(round(abs($variation), 2) != 0) {
				$error = ValidationException::withMessages(
					[
						'transactions' => [
							'credit amount should match debit amount',
						],
					]
				);
				
				throw $error;
			}
			
			return $creditAmount;
			
		}
		
		private function updateUserBalance($type = 'client', $transactionData, Account $account)
		{
			$transactionType = $transactionData['type'];
			$amount = $transactionData['amount'];
			$userId = $transactionData['user_id'];
			
			$user = User::find($userId);
			if($user != null) {
				if($type == 'client') {
					$effect = $transactionType == 'debit' ? 'increase' : 'decrease';
					dispatch_now(new UpdateClientBalanceJob($user, $amount, $effect));
				} else {
					$effect = $transactionType == 'credit' ? 'increase' : 'decrease';
					dispatch_now(new UpdateVendorBalanceJob($user, $amount, $effect));
				}
			}
			
		}
	}
