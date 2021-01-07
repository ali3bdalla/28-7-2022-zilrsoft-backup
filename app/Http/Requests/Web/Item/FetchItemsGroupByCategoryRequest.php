<?php
	
	namespace App\Http\Requests\Web\Item;
	
	use App\Models\Category;
	use App\Models\Item;
	use App\Models\ItemFilters;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FetchItemsGroupByCategoryRequest extends FormRequest
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
			$query = Item::query();
			
			
			if($this->has('name') && $this->filled('name')) {
				$searchArray = explode(' ' , $this->input('name'));
				foreach ($searchArray as $searchKey)
				{
					$query->where(function($subQuery) use($searchKey ) {
						return $subQuery->where('ar_name', 'ILIKE', '% ' . $searchKey . '%')->orWhere('name', 'ILIKE', '%' . $searchKey . '%');
					});
				}
				
			}
			// if($this->has('categoryId') && $this->filled('categoryId')) {
			// 	$category = Category::find($this->input('categoryId'));
			// 	if(!empty($category)) {
			// 		// $categoryIds = $category->getChildrenIncludeMe();
			// 		$query = $query->whereIn('category_id', $category->id);
			// 	}
				
			// }
			
			if($this->has('filters') && $this->filled('filters') && $this->input('filters') != []) {
				$collectionsItemsFilterResults = ItemFilters::whereIn('filter_value', $this->input('filters'))->select('item_id', 'filter_value')->get();
				$attributes = $this->input('filters');
				$finalCollections = [];
				
				foreach($collectionsItemsFilterResults as $result) {
					$finalCollections[$result['item_id']][] = $result['filter_value'];
				}
				$resultCollections = [];
				foreach($finalCollections as $key => $finalCollect) {
					if(count($finalCollect) >= count($attributes))
						$resultCollections[] = $key;
					
				}
				
				$query = $query->whereIn('id', $resultCollections);
			}
			
			$items = $query->with('category')->take(5)->get();
			
			$categoriesHas = $query->take(100)->pluck('category_id')->unique()->toArray();

			return [
				'items' => $items,
				'categories_group' => Category::whereIn('id',$categoriesHas)->get()
			];
		}
	}
