<?php

namespace Modules\Web\Http\Requests\Category;

use App\Category;
use Illuminate\Foundation\Http\FormRequest;

class ApiGetSubCategoriesRequest extends FormRequest
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

    public function getData(Category $category)
    {
        $ids = $category->returnNestedTreeIds($category);
        unset($ids[0]);

        return Category::whereIn('id',$ids)->get();
    }
}
