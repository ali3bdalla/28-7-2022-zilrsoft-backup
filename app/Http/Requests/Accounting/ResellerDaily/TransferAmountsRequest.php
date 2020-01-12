<?php
	
	namespace App\Http\Requests\Accounting\ResellerDaily;
	
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
			
		}
	}
