<?php

namespace App\Http\Requests\Web\Item;

use App\Models\Category;
use App\Models\FilterValues;
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
//            'filters_values' => 'array',
//            'filters_values.*' => 'required|integer|exists:filter_values,id'
        ];
    }

    public function getData()
    {
        $query = Item::query();

        if ($this->has('categoryId') && $this->filled('categoryId')) {
            $category = Category::find($this->input('categoryId'));
            if (!empty($category)) {
                 $query->where('category_id', $this->input('categoryId'));
            }
        }
        if ($this->has('name') && $this->filled('name')) {
             $query->where('name', 'ILIKE', '%' . $this->input('name') . '%')->orWhere('ar_name', 'ILIKE', '%' . $this->input('name') . '%');
        }

//        if ($this->has('filters_values')) {

            $filtersValues = $this->input('filters_values');//[100];
            $result = ItemFilters::whereIn('filter_value', $filtersValues)->pluck('filter_value', 'filter_id');
//            dd($result);
            foreach ($result as  $filterId => $valueId ) {
                  $query->whereHas('filters',function($query) use($filterId,$valueId){
                    $query->where([['filter_id', $filterId],['filter_value',$valueId]]);
                });
            }

//        }




        return $query->with('category')->paginate(18);


    }
}
