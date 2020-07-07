<?php

namespace Modules\Web\Http\Requests\Filter;

use App\Item;
use App\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class ApiGetFilterValuesRequest extends FormRequest
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

    public function getData($filter,$category)
    {

            $values_values = [];
            foreach ($filter->values as $value)
            {
                $itemsIds = ItemFilters::where([
                    [ 'filter_id',$filter->id],
                    [ 'filter_value',$value->id],
                ])->pluck('item_id');
                $value->items_count = Item::where('category_id',$category->id)->whereIn('id',$itemsIds)->count();
                $values_values[] = $value;
            }
            return $values_values;
    }
}

//