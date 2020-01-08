<?php
	
	namespace App\Http\Requests\Accounting\Voucher;
	
	use AliAbdalla\Tafqeet\Core\Tafqeet;
	use App\Account;
	use App\User;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\Rule;
	use Illuminate\Validation\ValidationException;
	
	class CreateVoucherRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create voucher');
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
				'voucher_type' => ['required',Rule::in(['transfer','cash','check'])],
				'org_account_id' => 'required|integer|exists:accounts,id',
				'user_account_id' => 'nullable|exists:user_gateways,id',
				'payment_type' => [Rule::in(['payment','receipt'])],
			];
		}
		
		public function save()
		{
			
			if ($this->voucher_type == 'transfer' && $this->payment_type == 'payment'){
				if ($this->user_account_id == null){
					throw ValidationException::withMessages([
						'user_account_id' => ['required field on transfer '],
					]);
				}
			}
			
			DB::beginTransaction();
			try{
				
				
				$organization_account = Account::findOrFail($this->org_account_id);
				$container = auth()->user()->organization->transactions_containers()->create(
					[
						'creator_id' => auth()->user()->id,
						'description' => $this->description,
						'amount' => $this->amount,
					]
				);
				
				$user = User::findOrFail($this->user_id);
				if ($this->payment_type == 'payment'){
					$user->update_vendor_balance('sub',$this->amount);
					$organization_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $this->amount,
						'user_id' => $this->user_id,
						'description' => 'vendor_balance',
						'container_id' => $container->id
					]);
					
					$vendor_account = auth()->user()->get_active_manager_account_for('vendors');
					
					
					$vendor_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $this->amount,
						'user_id' => $user->id,
						'container_id' => $container->id,
						'description' => 'vendor_balance'
					]);
					
					
				}else{
					
					$organization_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $this->amount,
						'user_id' => $this->user_id,
						'description' => 'client_balance',
						'container_id' => $container->id
					]);
					
					$client_account = auth()->user()->get_active_manager_account_for('clients');
					
					
					$client_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $this->amount,
						'user_id' => $user->id,
						'container_id' => $container->id,
						'description' => 'client_balance'
					]);
					
					
					$user->update_client_balance('add',$this->amount);
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
			}catch (Exception $exception){
				DB::rollBack();
				return $exception->getMessage();
			}
			
			
			return $organization_account;
		}
	}