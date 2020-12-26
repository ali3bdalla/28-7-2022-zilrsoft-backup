<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Models\Invoice;
	use Illuminate\Foundation\Http\FormRequest;
	
	class AdjustStockDatatableRequest extends FormRequest
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
			];
		}
		
		public function data()
		{
			
			$query = Invoice::where('invoice_type','stock_adjust')->with('creator','items');
			
			if ($this->has('id') && $this->filled('id')){
				$query = $query->where('id',$this->id);
			}
			
			if ($this->has('isDeleted') && $this->filled('isDeleted')){
				$query = $query->where('is_deleted',$this->input("isDeleted"));
			}

//
			if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')){
				$query = $query->orderBy($this->orderBy,$this->orderType);
			}else{
				$query = $query->orderByDesc("id");
			}
			
			
			if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")
				) >= 1 && intval($this->input('itemsPerPage')) <= 100){
				return $query->paginate(intval($this->input('itemsPerPage')));
			}else{
				return $query->paginate(20);
				
			}
			
			
		}
	}
