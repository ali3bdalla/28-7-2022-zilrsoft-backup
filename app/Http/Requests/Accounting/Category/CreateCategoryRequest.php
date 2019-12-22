<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use App\Category;
	use App\Events\CategoryCreatedEvent;
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
				'name' => "required|min:3|string",
				'ar_name' => "required|min:3|string",
				'description' => "required|min:3|string",
				'ar_description' => "required|min:3|string",
				'parent_id' => "required|integer",
			
			];
		}
		
		public function save()
		{
			
			// for testing use when there is no any
			$data = $this->except('_token');
			$data['organization_id'] = $this->user()->organization_id;
			$category = $this->user()->categories()->create($data);
			
//
//			if ($this->isClone && $this->cloned_category){
//				$parent_category = Category::find($this->cloned_category);
//				$category->cloneFiltersFromAnotherCategory($parent_category);
//			}
			
//			broadcast(new CategoryCreatedEvent($category));
			
		}
	}
