<?php
	
	namespace App\Http\Requests\Vouchers;
	
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
		 * @var mixed
		 */
		private $user_id;
		
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
				//
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
			
			
			DB::beginTransaction();
			try {
				if($this->voucher_type == 'transfer' && $this->payment_type == 'payment') {
					if($this->user_account_id == null) {
		
						
						$error = ValidationException::withMessages(
							[
								'user_account_id' => ['identity should have bank account'],
							]
						);
						
						throw $error;
					}
				}
				$vendorAccount = Account::where('slug', 'vendors')->first();
				$clientAccount = Account::where('slug', 'clients')->first();
				$loggedUser = $this->user();
				$organizationAccount = Account::findOrFail($this->input('org_account_id'));
				$user = User::find($this->input('user_id'));
				$container = TransactionsContainer::create(
					[
						'creator_id' => $loggedUser->id,
						'description' => $this->input('description'),
						'amount' => (float)$this->input('amount'),
						'organization_id' => $loggedUser->organization_id,
					]
				);
				
				if($this->payment_type == 'payment') {
					$organizationAccount->transactions()->create(
						[
							'creator_id' => $loggedUser->id,
							'organization_id' => $loggedUser->organization_id,
							'amount' => (float)$this->input('amount'),
							'user_id' => $this->input('user_id'),
							'description' => 'vendor_balance',
							'container_id' => $container->id,
							'type' => 'credit',
						]
					);
					$vendorAccount->transactions()->create(
						[
							'creator_id' => $loggedUser->id,
							'organization_id' => $loggedUser->organization_id,
							'amount' => (float)$this->input('amount'),
							'user_id' => $user->id,
							'container_id' => $container->id,
							'description' => 'vendor_balance',
							'type' => 'debit',
						]
					);
					
					dispatch_now(new UpdateVendorBalanceJob($user, (float)$this->input('amount'), 'decrease'));
					
				} else {
					
					$organizationAccount->transactions()->create(
						[
							'creator_id' => $loggedUser->id,
							'organization_id' => $loggedUser->organization_id,
							'amount' => (float)$this->input('amount'),
							'user_id' => $this->user_id,
							'description' => 'client_balance',
							'container_id' => $container->id,
							'type' => 'debit',
						
						]
					);
					
					$clientAccount->transactions()->create(
						[
							'creator_id' => $loggedUser->id,
							'organization_id' => $loggedUser->organization_id,
							'amount' => (float)$this->input('amount'),
							'user_id' => $user->id,
							'container_id' => $container->id,
							'description' => 'client_balance',
							'type' => 'credit',
						
						]
					);
					dispatch_now(new UpdateClientBalanceJob($user, (float)$this->input('amount'), 'decrease'));
					
				}
				
				$organizationAccount->payments()->create(
					[
						'creator_id' => $loggedUser->id,
						'organization_id' => $loggedUser->organization_id,
						'user_id' => $this->input('user_id'),
						'amount' => $this->input('amount'),
						'slug' => $this->input('voucher_type'),
						'description' => $this->input('description'),
						'payment_type' => $this->input('payment_type'),
						'user_account_id' => $this->input('user_account_id'),
					]
				);
				
				DB::commit();
			} catch(Exception $exception) {
				DB::rollBack();
				throw $exception;
			}
			
			// return $organization_account;
		}
	}
