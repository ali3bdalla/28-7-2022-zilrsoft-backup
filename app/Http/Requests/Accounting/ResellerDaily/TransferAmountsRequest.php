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
		
//			$receiver_account = A
			$container = auth()->user()->organization->transactions_containers()->create(
				[
					'creator_id' => auth()->user()->id,
					'description' => 'transfer_amount',
					'amount' => 0,
					'is_pending' => true,
				]
			);
//
//			$data = [];
//			$data['creator_id'] = auth()->user()->id;
//			$data['organization_id'] = auth()->user()->organization_id;
//			$data['debitable_id'] = $gateway['id'];
//			$data['debitable_type'] = Account::class;
//			$data['amount'] = $gateway['amount'];
//			$data['description'] = "close_account";
//			$container->transactions()->create($data);
//			$gateways_amount = $gateways_amount + $gateway['amount'];
//
//
//			$container->update([
//				'amount' => $debit_total
//			]);
//
			
			
		}
	}
