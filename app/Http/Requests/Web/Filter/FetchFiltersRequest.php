<?php
	
	namespace App\Http\Requests\Web\Filter;
	
	use App\Models\Category;
	use App\Models\CategoryFilters;
	use App\Models\CategoryFilterValues;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FetchFiltersRequest extends FormRequest
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
				//
			];
		}
		
		public function getData($items = [])
		{
			
			
			if($this->has('category_id') && $this->filled('category_id')) {
				$filtersValues = CategoryFilterValues::where('category_id', $this->input('category_id'))->get();
			} else {
				$categoriesIds = $items->pluck('category_id');
				$filtersValues = CategoryFilterValues::whereIn('category_id', $categoriesIds)->get();
			}
			
			
			$result = [];
			
			foreach($filtersValues as $categoryFilterValue) {
				if(!$categoryFilterValue->filter) {
					continue;
				}
				
				if($categoryFilterValue->value) {
					$result[$categoryFilterValue->filter_id]['values'][] = $categoryFilterValue->value;
					
				}
				if(count($result[$categoryFilterValue->filter_id]['values']) == 1) {
					$result[$categoryFilterValue->filter_id]['filter'] = $categoryFilterValue->filter;
				}
			}
			
			return array_splice($result, 1);
		}
	}
