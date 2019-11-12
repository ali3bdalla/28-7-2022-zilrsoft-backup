<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \App\Category;
use App\Events\CategoryCreatedEvent;

class CreateCategoryForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return $this->user()->isAuthorizedTo('create-category');
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
            'name'=>"required|unique:categories,name|min:3|string",
            'ar_name'=>"required|unique:categories,ar_name|min:3|string",
            'description'=>"required|unique:categories,description|min:3|string",
            'ar_description'=>"required|unique:categories,ar_description|min:3|string",
            'parent_id'=>"required|integer",
        ];
    }


    public function save(){

        // for testing use when there is no any
        $data = $this->except('_token');
        $data['organization_id'] = $this->user()->organization_id;
        $category = $this->user()->categories()->create($data);


        if($this->isClone && $this->cloned_category){
            $parent_category = Category::find($this->cloned_category);
            $category->cloneFiltersFromAnotherCategory($parent_category);
        }

        broadcast(new CategoryCreatedEvent($category));

    }
}
