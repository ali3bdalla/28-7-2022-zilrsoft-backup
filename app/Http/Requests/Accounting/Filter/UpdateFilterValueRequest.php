<?php
	
	namespace App\Http\Requests\Accounting\Filter;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class UpdateFilterValueRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create filter') || $this->user()->can("edit filter");
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
				'name' => 'required|string',
				'ar_name' => 'required|string',
				'filter_id' => 'required|integer|exists:filters,id',
			];
		}
		
		
	}
