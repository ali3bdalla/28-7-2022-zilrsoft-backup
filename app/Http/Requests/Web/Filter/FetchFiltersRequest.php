<?php

namespace App\Http\Requests\Web\Filter;

use App\Models\CategoryFilters;
use App\Models\CategoryFilterValues;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class FetchFiltersRequest extends FormRequest
{
    private $result = [];
    private $servedValues = [];

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
        ];
    }

    public function getData($items = [])
    {


        $items = collect($items);

        if ($this->has('category_id') && $this->filled('category_id') && $this->input('category_id') > 0) {
            $itemsIdList = Item::where('category_id', $this->input('category_id'))->pluck('id')->toArray();
            $filtersList = CategoryFilterValues::where('category_id', $this->input('category_id'))->groupBy('filter_id')->pluck('filter_id')->toArray();
        } else {
            $categoryArrayList = $items->pluck('category_id');

            $filtersList = CategoryFilters::whereIn('category_id', $categoryArrayList)->groupBy('filter_id')->pluck('filter_id')->toArray();
            $itemsIdList = Item::whereIn('category_id', $categoryArrayList)->pluck('id')->toArray();
        }



        // dd($filtersList );
        $filterValueList = ItemFilters::whereIn('item_id', $itemsIdList)
            ->whereIn('filter_id', $filtersList)
            ->select('filter_id', 'filter_value')
            ->groupBy(['filter_id', 'filter_value'])
            ->pluck('filter_value');

        FilterValues::whereIn('id', $filterValueList)->each(function ($item) {
            if (!in_array($item->id, $this->servedValues) && $item->filter) {
                $this->result[$item->filter_id]['filter'] = $item->filter;
                $this->result[$item->filter_id]['values'][] = $item;
                $this->servedValues[] = $item->id;
            }
        });

        return $this->result;

    }
}
