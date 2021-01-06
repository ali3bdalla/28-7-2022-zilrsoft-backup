<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use App\Models\Category;
	use App\Models\CategoryFilters;
	use App\Models\Filter;
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
				'name' => "required|min:2|string|organization_unique:App\Models\Category,name,NULL,id,deleted_at,NULL",
				'ar_name' => "required|min:2|string|organization_unique:App\Models\Category,ar_name,NULL,id,deleted_at,NULL",
				'description' => "required|min:2|string",
				'ar_description' => "required|min:2|string",
				'parent_id' => "required|integer",
				'cloned_category' => 'nullable|integer|organization_exists:App\Models\Category,id',
				'is_available_online' => 'nullable',
				"sorting" => "nullable|integer",
				"image" => "nullable|image"
			];
		}
		
		public function save()
		{
			
			$data = $this->only('name', 'ar_name', 'description', 'ar_description', 'parent_id',"sorting");
			$data['is_available_online'] = $this->input('is_available_online') == 'on';
			$data['organization_id'] = $this->user()->organization_id;
			$category = $this->user()->categories()->create($data);
			if($this->has('isCloned') && $this->has('cloned_category')) {
				$parent_category = Category::findOrFail($this->input("cloned_category"));
				if(!empty($parent_category)) {
					
					$filters = $parent_category->filters()->pluck('filter_id');
					$category->filters()->attach(
						$filters,
						[
							'organization_id' => auth()->user()->organization_id,
							'creator_id' => auth()->user()->id,
							'sorting' => 0
						]
					);
				}
				
				
			}
			

			if($this->hasFile('image'))
			{
				$imageUrl = $this->file('image')->store('images/categories', ['disk' => 'spaces', 'visibility' => 'public']);
				$category->update([
					'image' => $imageUrl
				]);
			}


			$requiredFilter = Filter::where('is_required_filter', true)->pluck('id')->toArray();
			$categoryFilters = CategoryFilters::where('category_id', $category->id)->pluck('filter_id')->toArray();
			
			foreach($requiredFilter as $filterId) {
				if(!in_array($filterId, $categoryFilters)) {
					$category->filters()->attach(
						$filterId, [
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'sorting' => 0
					]
					);
				}
			}
			
			
			return redirect(route('accounting.categories.index'));
		}
		
	}
