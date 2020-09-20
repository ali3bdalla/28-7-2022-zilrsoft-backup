<?php

namespace Modules\Web\Http\Requests\Filter;

use App\Models\Category;
use App\Models\CategoryFilters;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemFilters;
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
        $filtersArrayExists = $this->has('filters') && $this->filled('filters') && $this->input('filters') != [];
        $filtersValuesArrayExists = $this->has('values') && $this->filled('values') && $this->input('values') != [];
//        if($filtersArrayExists)
//            $query = $query->whereIn('filter_id',$this->input('filters'));
////
        if($filtersValuesArrayExists)
            $query = $query->whereIn('filter_value',$this->input('values'));

        $queryPluck = $query;
        $itemsIds =  $queryPluck->pluck('item_id');
        $itemsIdsInSameCategory =  Item::whereIn('id',$itemsIds)->where('category_id',$this->input('category_id'))->pluck('id');
        $data =  ItemFilters::whereIn('item_id',$itemsIdsInSameCategory)->select('filter_id','filter_value')->distinct()->get();
        $filtersWithValues = [];

        foreach ($data as $row)
        {


            if($filtersArrayExists && in_array($row->filter_id,$this->input('filters')))
            {

                if($filtersValuesArrayExists && in_array($row->filter_value,$this->input('values')))
                {
                    $filtersWithValues[$row->filter_id][] = $row->filter_value;
                }else {
                    $filtersWithValues[$row->filter_id][] = $row->filter_value;
                }
            }
           else
           {
               $filtersWithValues[$row->filter_id][] = $row->filter_value;
           }

        }
//        return  $filtersWithValues;
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
