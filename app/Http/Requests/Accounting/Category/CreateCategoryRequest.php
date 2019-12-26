<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use App\Category;
	use Illuminate\Foundation\Http\FormRequest;
	
	class CreateCategoryRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create category');
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
				'name' => "required|min:3|string|unique:categories,name",
				'ar_name' => "required|min:3|string|unique:categories,ar_name",
				'description' => "required|min:3|string",
				'ar_description' => "required|min:3|string",
				'parent_id' => "required|integer",
				'cloned_category' => 'nullable|integer|exists:categories,id'
			
			];
		}
		
		public function save()
		{
			
			
			
			// for testing use when there is no any
			$data = $this->except('_token');
			$data['organization_id'] = $this->user()->organization_id;
			$category = $this->user()->categories()->create($data);
			if ($this->has('isCloned') && $this->has('cloned_category')){
				$parent_category = Category::findOrFail($this->input("cloned_category"));
				if(!empty($parent_category))
				{
					
					$filters = $parent_category->filters()->pluck('filter_id');
					$category->filters()->attach($filters,
						[
							'organization_id' => auth()->user()->organization_id,
							'creator_id' => auth()->user()->id,
							'sorting' => 0
						]
					);
				}
				
				
			}
			
			
			
		}
		
	}
