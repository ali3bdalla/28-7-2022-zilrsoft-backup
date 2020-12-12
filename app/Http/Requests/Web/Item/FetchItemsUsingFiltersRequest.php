<?php
	
	namespace App\Http\Requests\Web\Item;
	
	use App\Models\Category;
	use App\Models\Item;
	use App\Models\ItemFilters;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FetchItemsUsingFiltersRequest extends FormRequest
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
				'filters_values' => 'array',
				'filters_values.*' => 'required|integer|exists:item_filters,filter_value'
			];
		}
		
		public function getData()
		{
			$query = new Item;
			
			
			if($this->has('filters_values') && $this->filled('filters_values') && $this->input('filters_values') != []) {
				$collectionsItemsFilterResults = ItemFilters::whereIn('filter_value', $this->input('filters_values'))->select('item_id', 'filter_value', 'filter_id')->get();
				
				$itemsFiltersValuesGroupByFilter = [];
				foreach($collectionsItemsFilterResults as $itemFilterValue) {
					$itemsFiltersValuesGroupByFilter[$itemFilterValue->filter_id][] = $itemFilterValue->filter_value;
				}
				
				foreach($itemsFiltersValuesGroupByFilter as $key => $values) {
					$itemsIds = ItemFilters::where('filter_id', $key)->whereIn('filter_value', $values)->pluck('item_id');
					$query = $query->whereIn('id', $itemsIds);
				}
////				return $itemsFiltersValuesGroupByFilter;
//				$attributes = $this->input('filters_values');
//				$finalCollections = [];
//
//				foreach($collectionsItemsFilterResults as $result) {
//					$finalCollections[$result['item_id']][] = $result['filter_value'];
//				}
//				$resultCollections = [];
//				foreach($finalCollections as $key => $finalCollect) {
//					if(count($finalCollect) >= count($attributes))
//						$resultCollections[] = $key;
//				}
////
			}
			
			
			if($this->has('categoryId') && $this->filled('categoryId')) {
				$category = Category::find($this->input('categoryId'));
				if(!empty($category)) {
					$query = $query->where('category_id', $this->input('categoryId'));
				}
			}
			if($this->has('name') && $this->filled('name')) {
				$query = $query->where('name', 'ILIKE', '%' . $this->input('name') . '%')->orWhere('ar_name', 'ILIKE', '%' . $this->input('name') . '%');
			}
			
			
			return $query->with('category')->paginate(18);
			
			
		}
	}
