<?php
	
	namespace App\Http\Requests\Accounting\Sale;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class ReturnSaleRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit sale');
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
			];
		}
		
		public function makeReturn($baseInvoice)
		{
		
		}
	}
