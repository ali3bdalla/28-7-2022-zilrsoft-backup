<?php

namespace App\Http\Requests\Accounting\Category;

use App\Models\Category;
use App\Models\CategoryFilters;
use App\Models\Filter;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user()->can('edit category');
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
			'is_available_online' => 'nullable',
			"sorting" => "nullable|integer",
			"image" => "nullable|image"

		];
	}

	public function update(Category $category)
	{

		$isAvailableOnline = $this->input('is_available_online') == 'on';
		$data = $this->only('name', 'ar_name', 'description', 'ar_description', 'parent_id', "sorting");
		$data['is_available_online'] = $isAvailableOnline;
		$category->update($data);

		Category::whereIn('id', $category->getChildrenIncludeMe())->update(
			[
				'is_available_online' => $isAvailableOnline
			]
		);


		if ($this->hasFile('image')) {
			$imageUrl = $this->file('image')->store('images/categories',  'public');
			$category->update([
				'image' => $imageUrl
			]);
		}
		if ($category->parent) {
			$category->parent->updateHashMap();
		}
		$category->updateHashMap();


		$requiredFilter = Filter::where('is_required_filter', true)->pluck('id')->toArray();
		$categoryFilters = CategoryFilters::where('category_id', $category->id)->pluck('filter_id')->toArray();

		foreach ($requiredFilter as $filterId) {
			if (!in_array($filterId, $categoryFilters)) {
				$category->filters()->attach(
					$filterId,
					[
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'sorting' => 0
					]
				);
			}
		}

		foreach ($category->items as $item) {
			if ($item->shouldBeSearchable())
				$item->searchable();
			else
				$item->unsearchable();
		}

		return redirect(route('accounting.categories.index'));
	}
}
