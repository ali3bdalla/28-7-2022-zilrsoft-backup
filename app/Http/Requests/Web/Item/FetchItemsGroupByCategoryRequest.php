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
			$query = Item::query();
			

			$query = $this->apply($query);
			
			$items = $query->with('category')->take(5)->get();
			
			$categoriesHas = $query->pluck('category_id')->unique()->toArray();

			return [
				'items' => $items,
				'categories_group' => Category::whereIn('id',$categoriesHas)->get()
			];
		}
	}
