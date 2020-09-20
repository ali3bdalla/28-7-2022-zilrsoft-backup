<?php

namespace Modules\Web\Http\Requests\Item;

use App\Models\Category;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Database\Eloquent\Builder;
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
            'attributes' => 'array|nullable',
            'attributes.*' => 'required|integer|exists:filter_values,id',
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

//

        if ($this->has('attributes') && $this->filled('attributes') && $this->input('attributes') != []){
            $collectionsItemsFilterResults = ItemFilters::whereIn('filter_value',$this->input('attributes'))->select('item_id','filter_value')->get();
//            return $collectionsItemsFilterResults;
            $attributes = $this->input('attributes');
            $finalCollections = [];

            foreach ($collectionsItemsFilterResults as $result)
            {
                $finalCollections[$result['item_id']][] = $result['filter_value'];
            }
            $resultCollections = [];
            foreach ($finalCollections as $key => $finalCollect)
            {
                if(count($finalCollect) >= count($attributes))
                    $resultCollections[] = $key;

            }

            $query = $query->whereIn('id',$resultCollections);
        }

        return  $query->with('creator','data','items','category')->paginate(18);
    }
}
