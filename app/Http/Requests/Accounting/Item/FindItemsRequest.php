<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\Models\Item;
	use App\Models\ItemSerials;
	use Illuminate\Database\Eloquent\Builder;
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
			
			
			$limit = 6;
			
			if ($this->has('invoice_type') && $this->filled('invoice_type') && $this->input('invoice_type') == 'dashbaord'){
				$limit = 10;
			}
			$query = Item::where('is_expense',false)->with('data','items')->withCount(['history' => function (Builder $query){
				$query->where('invoice_type','sale');
			}])->orderBy('history_count','desc')->limit($limit);
			
			
			if ($this->has('barcode_or_name_or_serial') && $this->filled('barcode_or_name_or_serial')){
				$query = $query->where('barcode','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%')
					->orWhere('ar_name','LIKE','%'.$this->input('barcode_or_name_or_serial').'%');
			}
			$result = $query->take($limit)->get();
			
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
