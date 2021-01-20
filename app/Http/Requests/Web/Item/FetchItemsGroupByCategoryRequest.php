<?php

namespace App\Http\Requests\Web\Item;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class FetchItemsGroupByCategoryRequest extends FormRequest
{


	use ItemSearch;
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
		$searchKeywords = explode(' ', $this->input('name'));
		$categoriesEntities = Category::whereHas('items', function ($query) {
			$this->apply($query);
		})->get();

		$categories = [];
		foreach ($categoriesEntities as $entity) {

			$itemsQuery = Item::where('category_id', $entity->id);
			$items = $this->apply($itemsQuery)->get();
			$entity['search_keywords'] = $this->getCategorySearchKeywords($entity, $items, $searchKeywords);
			$entity['result_items_count'] = count($items);
			$categories[] = $entity->toArray();
		}


		return $categories;
		// ->get()
		// dd(	$categories);

		// // $query = $this->apply($query);

		// // $items = $query->with('category')->get();

		// // $categoriesHas = $query->pluck('category_id')->unique()->toArray();

		// return [
		// 	// 'items' => $items,
		// 	'categories_group' => Category::whereIn('id',$categoriesHas)->get()
		// ];
	}

	public function getCategorySearchKeywords($entity, $items, $searchKeywords)
	{
		$entitySearchKeywords = "";


		if( $searchKeywords == [] || $searchKeywords[0] == "") return "";
		// شاحن ايفون
		foreach ($searchKeywords as $searchKeyword) {

			
			if (strpos($entitySearchKeywords, $searchKeyword)  === false ) 
			{
				if (strpos($entity->locale_name, $searchKeyword) !== false) {
					$entitySearchKeywords.=  " " . $searchKeyword;
				} else {
					foreach ($items as $item) {
						if (strpos($item->locale_name, $searchKeyword) !== false)
							$entitySearchKeywords.=  " " . $searchKeyword;
					}
				}
			}


			

		}

		return implode(" ",array_unique(explode(" ",$entitySearchKeywords)));
		// 
	}
}
