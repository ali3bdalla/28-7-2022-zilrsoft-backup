<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Models\Invoice;
	use Illuminate\Foundation\Http\FormRequest;
	
	class DatatableBeginningRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage inventory');
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
			
			$query = Invoice::where('invoice_type','beginning_inventory')->with('creator','items');
//
//			if ($this->has('barcode') && $this->filled('barcode')){
//				$query = $query->where('barcode','ILIKE','%'.$this->barcode.'%');
//			}
//
//			if ($this->has('creators') && $this->filled('creators')){
//				$query = $query->whereIn('creator_id',$this->creators);
//			}
//
//if ($this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
//				$this->filled('endDate')){
//				$_startDate = Carbon::parse($this->input("startDate"))->toDateString();
//				$_endDate = Carbon::parse($this->input("endDate"))->toDateString();
//
//
//				if ($_endDate === $_startDate){
//					$query = $query->whereDate('created_at',$_startDate);
//				}else{
//					$query = $query->whereBetween('created_at',[
//						$_startDate->toDateString(),
//						$_endDate->toDateString()
//					]);
//				}
//
//
//			}
//
//
//			if ($this->has('name') && $this->filled('name')){
//				$query = $query->where('name','ILIKE','%'.$this->name.'%')->orWhere('ar_name','ILIKE','%'.$this->name
//					.'%');
//			}
//
//
//
			
			if ($this->has('id') && $this->filled('id')){
				$query = $query->where('id',$this->id);
			}
//
//
//			if ($this->has('available_qty') && $this->filled('available_qty')){
//				$query = $query->where('available_qty',$this->available_qty);
//			}
//
//			if ($this->has('date') && $this->filled('date')){
//				$query = $query->where('date','ILIKE','%'.$this->date.'%');
//			}
//
//
//			if ($this->has('price') && $this->filled('price')){
//				$price = explode("-",$this->price);
//				if (count($price) >= 2){
//					$startPrice = $price[0];
//					$endPrice = $price[1];
//				}else{
//					$startPrice = $this->price;
//					$endPrice = $this->price;
//				}
//				$query = $query->whereBetween('price',[$startPrice,$endPrice]);
//			}
//
//
//			if ($this->has('price_with_tax') && $this->filled('price_with_tax')){
//				$price_with_tax = explode("-",$this->price_with_tax);
//				if (count($price_with_tax) >= 2){
//					$startPriceWithTax = $price_with_tax[0];
//					$endPriceWithTax = $price_with_tax[1];
//				}else{
//					$startPriceWithTax = $this->price_with_tax;
//					$endPriceWithTax = $this->price_with_tax;
//				}
//				$query = $query->whereBetween('price_with_tax',[$startPriceWithTax,$endPriceWithTax]);
////				$query = $query->where('price_with_tax',$this->price_with_tax);
//			}
////
//
//
//			if ($this->has('current_status') && $this->filled('current_status')){
//				if (in_array($this->input("current_status"),['active','pending'])){
//					$query = $query->where('status',$this->input("current_status"));
//				}else if ($this->input("current_status") == 'kits'){
//					$query = $query->where('is_kit',true);
//				}else{
//					$query = $query->whereIn('status',['active','pending']);
//				}
//
//			}
//
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
