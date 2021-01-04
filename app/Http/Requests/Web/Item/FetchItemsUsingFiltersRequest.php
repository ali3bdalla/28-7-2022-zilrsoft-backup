<?php

namespace App\Http\Requests\Web\Item;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

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
            'filters_values' => 'array',
//            'filters_values.*' => 'required|integer|exists:filter_values,id'
        ];
    }

    public function getData()
    {
        $query = Item::query();

        $table = (new Item())->getTable();

        if ($this->has('categoryId') && $this->filled('categoryId')) {
            $category = Category::find($this->input('categoryId'));
            if (!empty($category)) {
                $query->where('category_id', $this->input('categoryId'));
            }
        }
        if ($this->has('name') && $this->filled('name')) {
            $query->where('name', 'ILIKE', '%' . $this->input('name') . '%')->orWhere('ar_name', 'ILIKE', '%' . $this->input('name') . '%');
        }

        if ($this->has('filters_values')) {

            $filtersValues = $this->input('filters_values');//[100];
            $result = ItemFilters::whereIn('filter_value', $filtersValues)->pluck('filter_value', 'filter_id');
            foreach ($result as $filterId => $valueId) {
                $query->whereHas('filters', function ($query) use ($filterId, $valueId) {
                    $query->where([['filter_id', $filterId], ['filter_value', $valueId]]);
                });
            }

        }


        if($this->has('order_by') && $this->filled('order_by') && Schema::hasColumn($table, $this->input('order_by')))
        {
            $sortDirection = $this->input('order_direction');

            if(!in_array($sortDirection,['desc','asc']))
            {
                $sortDirection = 'desc';
            }
            $query = $query->orderBy($this->input('order_by'),$sortDirection);
        }

        return $query->with('category','filters.filter', 'filters.value')->paginate(18);


    }
}
