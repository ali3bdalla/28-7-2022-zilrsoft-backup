<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\Item;
	use App\ItemSerials;
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
				'manage inventory',
				'create purchase',
				'create sale',
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
			
			
			$query = Item::where('is_expense',false)->with('data','items');
			if ($this->has('barcode_or_name_or_serial') && $this->filled('barcode_or_name_or_serial')){
				$query = $query->where('barcode','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('ar_name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%');
			}
			$result = $query->take(5)->get();
			
			if ($this->has('invoice_type') && $this->input("invoice_type") == 'sale'){
				
				if (count($result) == 0){
					$serail_data = ItemSerials::
					where('serial',$this->input('barcode_or_name_or_serial'))
						->whereIn('current_status',['available','r_sale','purchase'])
						->first();
					if (!empty($serail_data)){
						$item = $serail_data->item;
						$item->has_init_serial = true;
						$item->init_serial = $serail_data->fresh();
						$result[] = $item;
					}
				}
//
			}
			
			
			return $result;
		}
	}
