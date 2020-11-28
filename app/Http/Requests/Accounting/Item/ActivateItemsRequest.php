<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\Models\Item;
	use Illuminate\Foundation\Http\FormRequest;
	
	class ActivateItemsRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit item');
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
				'id_array' => 'required|array',
				'id_array.*' => 'required|integer|organization_exists:App\Models\Item,id',
			];
		}
		
		public function activate()
		{
			Item::whereIn('id',$this->id_array)->update([
				'status' => 'active'
			]);
		}
	}
