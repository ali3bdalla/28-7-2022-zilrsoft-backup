<?php
	
	namespace App\Http\Requests\Accounting\Charts;
	
	use App\Account;
	use Illuminate\Foundation\Http\FormRequest;
	
	class PeriodCloseAccoundRequest extends FormRequest
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
				'gateways.*.amount' => 'required|price',
				'period_sales_Amount' => 'required|price',
			];
		}
		
		public function save()
		{
			$temp_reseller_account = Account::where([
				['is_system_account',true],
				['slug','temp_reseller_account'],
			])->first();
			
			$container = auth()->user()->organization->transactions_containers()->create(
				[
					'creator_id' => auth()->user()->id,
					'description' => 'account_close',
					'amount' => 0,
				]
			);
			$short_shortage_amount = $this->getShortageAmount();
			


//
//
//			$gatewaysAmount = 0;
//			foreach ($this->input("gateways") as $gateway){
//				$gatewaysAmount = $gatewaysAmount + $gateway['amount'];
//
//				$data = [];
//				$data['creator_id'] = auth()->user()->id;
//				$data['organization_id'] = auth()->user()->organization_id;
//				$data['creditable_id'] = $temp_reseller_account->id;
//				$data['creditable_type'] = Account::class;
//				$data['amount'] = $gateway['amount'];
//				$data['debitable_id'] = $gateway['id'];
//				$data['debitable_type'] = Account::class;
//				$data['amount'] = $gateway['amount'];
//				$data['description'] = "close_account";
//				$container->transactions()->create($data);
//			}
		}
		
		public function getShortageAmount()
		{
			$gatewaysAmount = 0;
			foreach ($this->input("gateways") as $gateway){
				$gatewaysAmount = $gatewaysAmount + $gateway['amount'];
			}
			return $this->input("period_sales_Amount") - $gatewaysAmount;
		}
	}
