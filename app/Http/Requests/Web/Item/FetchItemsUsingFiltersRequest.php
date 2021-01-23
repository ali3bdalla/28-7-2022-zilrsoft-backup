<?php

namespace App\Http\Requests\Web\Item;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class FetchItemsUsingFiltersRequest extends FormRequest
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
            'filters_values' => 'array',
            "name" => "nullable|string"
        ];
    }

    public function getData()
    {
        $query = Item::query();

        $table = (new Item())->getTable();





        if ($this->has('category_id') && $this->filled('category_id')) {
            $category = Category::find($this->input('category_id'));

            if ($category) {
                $query  = $query->where('category_id', $this->input('category_id'));
            }
        }



        if ($this->has('parent_category_id') && $this->filled('parent_category_id')) {
            $category = Category::find($this->input('parent_category_id'));

            if ($category) {
                $query  = $query->whereIn('category_id', $category->getChildrenIncludeMe());
            }
        }



        if ($this->has('filters_values')) {

            $filtersValues = $this->input('filters_values');
            $result = ItemFilters::whereIn('filter_value', $filtersValues)->get();


            $filterValuesGroupedByFilters = [];

            foreach ($result  as $value) {
                $filterValuesGroupedByFilters[$value->filter_id][] = $value->filter_value;
            }
            foreach ($filterValuesGroupedByFilters as $filterId => $values) {
                $query  = $query->whereHas('filters', function ($query2) use ($filterId, $values) {
                    $query2->where([['filter_id', $filterId]])->whereIn('filter_value', $values);
                });
            }
        }

        $query->where(function($subQuery){
            $this->apply($subQuery);
        });

        if ($this->has('order_by') && $this->filled('order_by') && Schema::hasColumn($table, $this->input('order_by'))) {
            if($this->input('order_by') == "available_qty")
            {
                $query  =$query->where('available_qty','>=',1);
            }else
            {
                $sortDirection = $this->input('order_direction');

                if (!in_array($sortDirection, ['desc', 'asc'])) {
                    $sortDirection = 'desc';
                }
                $query  = $query->orderBy($this->input('order_by'), $sortDirection);
            }
            
        }

        return $query->with('category', 'filters.filter', 'filters.value')->paginate(18);
    }
}
