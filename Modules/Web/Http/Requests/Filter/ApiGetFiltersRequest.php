<?php

namespace Modules\Web\Http\Requests\Filter;

use App\Category;
use App\CategoryFilters;
use App\Filter;
use App\Item;
use App\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class ApiGetFiltersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'category_id' => 'required|integer|exists:categories,id',
            'filters' => 'nullable|array',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function getData()
    {
//        $category = Category::find($this->input('category_id'));
//        return $category->filters;
    }
}
