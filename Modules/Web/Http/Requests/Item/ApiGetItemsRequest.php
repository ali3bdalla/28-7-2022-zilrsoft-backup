<?php

namespace Modules\Web\Http\Requests\Item;

use App\Category;
use App\Item;
use App\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class ApiGetItemsRequest extends FormRequest
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

    public function getData()
    {

        $query = new Item;


        if ($this->has('attributes') && $this->filled('attributes')){
            $filterFinalCollection = [];
            foreach ($this->input('attributes') as  $filter){
                if (!empty($filter)){
                    $collection = json_decode($filter,true);
                    $filterFinalCollection[$collection['filter_id']][] = $collection['value_id'];
                }
            }


            foreach ($filterFinalCollection as $filterId => $valueArray){
                $itemsIds = ItemFilters::
                where('filter_id',$filterId)
                    ->whereIn('filter_value',collect($valueArray)
                    ->toArray())
                    ->pluck('item_id');
                $query = $query->whereIn('id',$itemsIds->toArray());
            }

        }


        if($this->has('category_id') && $this->filled('category_id'))
        {
            $category = Category::find($this->input('category_id'));
            if(!empty($category))
            {
                $categoryIds = $category->returnNestedTreeIds($category);
                $query = $query->whereIn('category_id',$categoryIds);
            }

        }

        if($this->has('categories_id_array') && $this->filled('categories_id_array'))
            $query = $query->whereIn('category_id',$this->input('categories_id_array'));

        return  $query->with('category')->paginate(18);
    }
}
