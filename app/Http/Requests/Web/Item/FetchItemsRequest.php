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
			$query = Item::query();
			
			
			
			
			
			if($this->has('name') && $this->filled('name')) {
				$query->where('name', 'ILIKE', '% ' . $this->input('name') . '%')->orWhere('ar_name', 'ILIKE', '% ' . $this->input('name') . '%');
			}

			if($this->has('categoryId') && $this->filled('categoryId')) {
				$category = Category::find($this->input('categoryId'));
				
				if($category) {
					$query->where('category_id',$this->input('categoryId'));
				}
				
			}
			return $query->with('category','filters.filter', 'filters.value')->paginate(18);
		}
		
		
	}
