<?php
	
	namespace App\Http\Requests\Web\Item;
	
	use App\Models\Category;
	use App\Models\Item;
	use App\Models\ItemFilters;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FetchItemsRequest extends FormRequest
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
		
		public function getData()
		{
			$query = new Item;
			
			
			if($this->has('categoryId') && $this->filled('categoryId')) {
				$category = Category::find($this->input('categoryId'));
				
				if(!empty($category)) {
//					$categoryIds = $category->getChildrenIncludeMe();
					$query = $query->where('category_id',$this->input('categoryId'));
				}
				
			}
			
			if($this->has('name') && $this->filled('name')) {
				$query = $query->where('name', 'ILIKE', '%' . $this->input('name') . '%')->orWhere('ar_name', 'ILIKE', '%' . $this->input('name') . '%');
			}
//
//
//
//			if($this->has('filters') && $this->filled('filters') && $this->input('filters') != []) {
//				$collectionsItemsFilterResults = ItemFilters::whereIn('filter_value', $this->input('filters'))->select('item_id', 'filter_value')->get();
//				$attributes = $this->input('filters');
//				$finalCollections = [];
//
//				foreach($collectionsItemsFilterResults as $result) {
//					$finalCollections[$result['item_id']][] = $result['filter_value'];
//				}
//				$resultCollections = [];
//				foreach($finalCollections as $key => $finalCollect) {
//					if(count($finalCollect) >= count($attributes))
//						$resultCollections[] = $key;
//
//				}
//
//				$query = $query->whereIn('id', $resultCollections);
//			}
//
			
			return $query->with('category')->paginate(18);
		}
		
		
	}
