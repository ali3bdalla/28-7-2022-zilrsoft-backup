<?php

namespace App\Http\Requests\Web\Item;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class FetchItemsRequest extends FormRequest
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
			// 'categoryId' => 'required|integer'
		];
	}

	public function getData()
	{
		$query = Item::where('category_id', $this->input('category_id'));
		$query = $this->apply($query);
		return $query->with('category', 'filters.filter', 'filters.value')->paginate(18);
	}
}
