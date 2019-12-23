<?php
	
	namespace App\Http\Requests\Accounting\Branch;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class UpdateBranchRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage branches');
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
				'name' => 'required|string|min:3',
				'ar_name' => 'required|string|min:3',
				'phone_number' => 'required|string|min:6',
			];
		}
	}
