<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\Item;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FindItemsRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can([
				'manage inventory'
			]);
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
				'barcode_or_name_or_serial' => 'nullable'
			];
		}
		
		public function results()
		{
			
			$query = Item::where('id','!=',0);
			
			if ($this->has('barcode_or_name_or_serial') && $this->filled('barcode_or_name_or_serial')){
				$query = $query->where('barcode','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('ar_name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%');
			}
			
			return $query->take(5)->get();
		}
	}
