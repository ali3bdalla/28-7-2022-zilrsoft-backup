<?php
	
	namespace App\Http\Requests\Accounting\Category;
	
	use App\Models\Category;
    use Illuminate\Foundation\Http\FormRequest;
	
	class UpdateCategoryRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit category');
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
				'name' => "required|min:3|string",
				'ar_name' => "required|min:3|string",
				'description' => "required|min:3|string",
				'ar_description' => "required|min:3|string",
				'parent_id' => "required|integer",
                'is_available_online' => 'nullable'
			
			];
		}

        public function update(Category $category)
        {
            $data =$this->only('name','ar_name','description','ar_description','parent_id');
            $data['is_available_online'] =  $this->input('is_available_online') == 'on';
            $category->update($data);

            return redirect(route('accounting.categories.index'));

		}
	}
