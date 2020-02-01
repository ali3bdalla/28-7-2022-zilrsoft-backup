<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class CreateAdjustStockRequest extends FormRequest
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
				'items' => 'required|array',
				'items.*.id' => ['required','integer','exists:items,id'],
				'items.*.qty' => 'required|integer|min:1',
			];
		}
		
	}
