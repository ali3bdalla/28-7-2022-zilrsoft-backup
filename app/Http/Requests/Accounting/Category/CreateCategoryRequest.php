<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use App\Models\Category;
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
				'name' => "required|min:2|string|unique:categories,name,NULL,id,deleted_at,NULL",
				'ar_name' => "required|min:2|string|unique:categories,ar_name,NULL,id,deleted_at,NULL",
				'description' => "required|min:2|string",
				'ar_description' => "required|min:2|string",
				'parent_id' => "required|integer",
				'cloned_category' => 'nullable|integer|exists:categories,id',
				'is_available_online' => 'nullable',

			];
		}
		
		public function save()
		{

			$data = $this->only('name','ar_name','description','ar_description','parent_id');
			$data['is_available_online'] =  $this->input('is_available_online') == 'on';
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
			
			

			return  redirect(route('accounting.categories.index'));
		}
		
	}
