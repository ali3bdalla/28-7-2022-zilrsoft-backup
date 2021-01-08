<?php

namespace App\Http\Requests\Store\Filter;

use Illuminate\Foundation\Http\FormRequest;


use App\Models\Category;
use App\Models\CategoryFilters;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemFilters;


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
            "items" => "nullable|array",
            "items.*.id" => "required|integer|exists:items,id"
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

        


        // 

        // $itemsfiltersValues = ItemFilters::whereIn('item_id',collect($this->input('items'))->pluck('id') )->get();


        // $result = [];
        // $insertedValues = [];

        // foreach ($itemsfiltersValues as $filterValue) {
        //     if(!in_array($filterValue->filter_value,$insertedValues))
        //     {
        //         $result[$filterValue->filter_id]["filter"] = $filterValue->filter;
        //         $result[$filterValue->filter_id]["values"][] =   $filterValue->load('value');
        //         $insertedValues[] = $filterValue->filter_value;
        //     }
            
        // }


        $itemsfiltersValues = ItemFilters::whereIn('item_id',collect($this->input('items'))->pluck('id') )->select('filter_value')->groupBy('filter_value')->get();


        $result = [];
        foreach ($itemsfiltersValues as $valueId) {
            $value = FilterValues::find($valueId->filter_value);
            if($value)
            {
                $result[$value->filter_id]["filter"] = $value->filter;
                $result[$value->filter_id]["values"][] =   $value;
            }
            
        }
        // return $result;
        return $result;
        // $query = ItemFilters::query();

        // $filtersArrayExists = $this->has('filters') && $this->filled('filters') && $this->input('filters') != [];
        // $filtersValuesArrayExists = $this->has('values') && $this->filled('values') && $this->input('values') != [];

        // if($filtersValuesArrayExists)
        //     $query = $query->whereIn('filter_value',$this->input('values'));

        // $queryPluck = $query;
        // $itemsIds =  $queryPluck->pluck('item_id')->unique()->toArray();

        // if($this->has('category_id') && $this->filled('category_id'))
        //      $itemsIdsInSameCategory =  Item::whereIn('id',$itemsIds)->where('category_id',$this->input('category_id'))->pluck('id');
        // else 
        //     $itemsIdsInSameCategory = $itemsIds;

        // $data =  ItemFilters::whereIn('item_id',$itemsIdsInSameCategory)->select('filter_id','filter_value')->distinct()->get();


        // $filtersWithValues = [];

        // foreach ($data as $row)
        // {


        //     if($filtersArrayExists && in_array($row->filter_id,$this->input('filters')))
        //     {

        //         if($filtersValuesArrayExists && in_array($row->filter_value,$this->input('values')))
        //         {
        //             $filtersWithValues[$row->filter_id][] = $row->filter_value;
        //         }else {
        //             $filtersWithValues[$row->filter_id][] = $row->filter_value;
        //         }
        //     }
        //    else
        //    {
        //        $filtersWithValues[$row->filter_id][] = $row->filter_value;
        //    }

        // }

        // $result = [];
        // foreach ($filtersWithValues as $key => $val)
        // {
        //     $result[] = Filter::where('id',$key)->with(['values' => function($query) use ($val){
        //          $query->whereIn('id',$val);
        //     }])->first();
        // }

        return $result;

    }
}
