<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class UpdateCategoryRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit category');
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
				'name' => "required|min:3|string",
				'ar_name' => "required|min:3|string",
				'description' => "required|min:3|string",
				'ar_description' => "required|min:3|string",
				'parent_id' => "required|integer",
			
			];
		}
	}
