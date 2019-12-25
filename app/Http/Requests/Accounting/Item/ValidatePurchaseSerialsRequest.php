<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class ValidatePurchaseSerialsRequest extends FormRequest
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
				'serials' => 'required|array',
				'serials.*' => 'required|string|min:3|unique:item_serials,serial',
			];
		}
		
		public function good()
		{
			return $this->serials;
		}
	}
