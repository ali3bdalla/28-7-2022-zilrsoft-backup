<?php
	
	namespace App\Http\Requests\Accounting\Filter;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class CreateFilterRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create filter');
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
				'name' => 'required|string|organization_unique:App\Models\Filter,name',
				'ar_name' => 'required|string|organization_unique:App\Models\Filter,ar_name',
			];
		}
	}
