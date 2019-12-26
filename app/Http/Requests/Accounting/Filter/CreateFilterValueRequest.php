<?php
	
	namespace App\Http\Requests\Accounting\Filter;
	
	use Illuminate\Foundation\Http\FormRequest;
	
	class CreateFilterValueRequest extends FormRequest
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
				'name' => 'required|string|unique:filter_values,name',
				'ar_name' => 'required|string|unique:filter_values,name',
				'filter_id' => 'required|integer|exists:filters,id',
			];
		}
		
		public function save()
		{
			$data = $this->only('name','ar_name','filter_id');
			$data['organization_id'] = auth()->user()->organization_id;
			$value = auth()->user()->filters_values()->create(
				$data
			);
			
			$value['creator'] = auth()->user();
			$value['locale_name'] = $value['locale_name'];
			return $value;
			
		}
	}
