<?php

namespace Modules\Web\Http\Requests\Filter;

use App\Category;
use App\CategoryFilters;
use App\Filter;
use App\FilterValues;
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
            'category_id' => 'integer|exists:categories,id',
            'filters' => 'nullable|array',
            'values' => 'nullable|array',
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

        $query = ItemFilters::where('id','>',0);
//        if($this->has('filters') && $this->filled('filters') && $this->input('filters') != [])
//            $query = $query->whereIn('filter_id',$this->input('filters'));
////
        if($this->has('values') && $this->filled('values') && $this->input('values') != [])
            $query = $query->whereIn('filter_value',$this->input('values'));




        $queryPluck = $query;
        $itemsIds =  $queryPluck->pluck('item_id');

        $itemsIdsInSameCategory =  Item::whereIn('id',$itemsIds)->where('category_id',$this->input('category_id'))->pluck('id');


//        return $itemsIdsInSameCategory;
        //
//
        $data =  ItemFilters::whereIn('item_id',$itemsIdsInSameCategory)->select('filter_id','filter_value')->distinct()->get();
//        $data =  $query->whereIn('item_id',$itemsIdsInSameCategory)->select('filter_id','filter_value')->distinct()->get();

//        return $data;
        $filtersWithValues = [];
        foreach ($data as $row)
        {
            $filtersWithValues[$row->filter_id][] = $row->filter_value;
        }


        $result = [];
        foreach ($filtersWithValues as $key => $val)
        {
            $result[] = Filter::where('id',$key)->with(['values' => function($query) use ($val){
                 $query->whereIn('id',$val);
            }])->first();
        }



        return $result;





//        $category = Category::find($this->input('category_id'));
//        return $category->filters()->with('values')->get();
    }
}
