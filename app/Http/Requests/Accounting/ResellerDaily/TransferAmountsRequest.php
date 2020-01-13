<?php
	
	namespace App\Http\Requests\Accounting\ResellerDaily;
	
	use App\Account;
	use Illuminate\Foundation\Http\FormRequest;
	
	class TransferAmountsRequest extends FormRequest
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
				//
				'amount' => 'required|price',
				'gateway_id' => 'required|integer|exists:accounts,id',
				'receiver_id' => 'required|integer|exists:managers,id',
				'receiver_gateway_id' => 'required|integer|exists:accounts,id',
			];
		}
		
		public function save()
		{
			
			$temp_reseller_account = Account::where([
				['is_system_account',true],
				['slug','temp_reseller_account'],
			])->first();
			
			$gateway = Account::find($this->input('gateway_id'));
			
			$remaining = $gateway->current_amount - $this->input('amount');
//			$receiver_account = A
			$container = auth()->user()->organization->transactions_containers()->create(
				[
					'creator_id' => auth()->user()->id,
					'description' => 'transfer_amount',
					'amount' => $this->input('amount'),
					'is_pending' => true,
				]
			);
			
			$manager = auth()->user();
			
			$manager->private_transactoins()->create([
				'organization_id' => auth()->user()->organization_id,
				'transaction_type' => "transfer",
				'transaction_container_id' => $container->id,
				'receiver_id' => $this->input('receiver_id'),
				'amount' => $this->input('amount'),
				'is_pending' => true,
			]);
			
			
			
			
//
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			$data['debitable_id'] = $this->input('receiver_gateway_id');
			$data['debitable_type'] = Account::class;
			$data['amount'] = $gateway->current_amount;
			$data['description'] = "close_account";
			$data['is_pending'] = true;
			$container->transactions()->create($data);
			
			
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			$data['creditable_id'] = $this->input('gateway_id');
			$data['creditable_type'] = Account::class;
			$data['is_pending'] = true;
			$data['amount'] = $this->input('amount');
			$data['description'] = "close_account";
			$container->transactions()->create($data);
			
			
			
			
			if ($remaining > 0){
				$data = [];
				$data['creator_id'] = auth()->user()->id;
				$data['organization_id'] = auth()->user()->organization_id;
				$data['debitable_id'] = $temp_reseller_account->id;
				$data['debitable_type'] = Account::class;
				$data['amount'] = $remaining;
				$data['description'] = "close_account";
				$data['is_pending'] = true;
				$container->transactions()->create($data);
			}

			
			
//			$container->update([
//				'amount' => $debit_total
//			]);
//
			
			
		}
	}
