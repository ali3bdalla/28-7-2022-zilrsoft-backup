<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\Models\Item;
	use Illuminate\Foundation\Http\FormRequest;
	
	class ValidateSerialRequest extends FormRequest
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
				'serials' => 'array|required',
				'serials.*' => 'string|min:1',
			];
		}
		
		public function sale()
		{
			$this->validate([
				'item_id' => 'required|integer|exists:items,id',
			]);
			
			$item = Item::find($this->item_id);
			return $serials = $item->serials()
				->whereIn('current_status',['available','return_sale'])
				->whereIn('serial',$this->serials)
				->pluck('serial');
//			return $serials->whereIn('serials',[
//				"VNC3B90549"
//			]);
			
		}
		
		public function purchase()
		{
		
		}
		
		public function retrun_sale()
		{
		
		}
		
		public function retrun_purchase()
		{
		
		}
	}
