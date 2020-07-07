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
            'category_id' => 'required|integer|exists:categories,id'
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
        $category = Category::find($this->input('category_id'));
//        foreach ($category->filters as $filter)
//        {
////            $values_values = [];
////            foreach ($filter->values as $value)
////            {
////                $itemsIds = ItemFilters::where([
////                    [ 'filter_id',$filter->id],
////                    [ 'filter_value',$value->id],
////                ])->pluck('item_id');
////                $value->items_count = Item::where('category_id',$category->id)->whereIn('id',$itemsIds)->count();
////                $values_values[] = $value;
////            }
//            $filter->values = $values_values;
//            $result[] = $filter;
//        }
        return $category->filters;
    }
}

//
//$category = Category::find($this->input('category_id'));
////        $ids = $category->returnNestedTreeIds($category);
//$result = [];
////        $filters = CategoryFilters::whereIn('category_id',$ids)->get();
//$filters = ;