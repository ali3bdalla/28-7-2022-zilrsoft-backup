<?php
	
	namespace App\Http\Requests\Accounting\Sale;
	
	use App\Invoice;
	use App\SaleInvoice;
	use Carbon\Carbon;
	use Illuminate\Foundation\Http\FormRequest;
	
	class DatatableRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('view sale');
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
			
			$query = Invoice::whereIn('invoice_type',['sale','r_sale'])->with('creator','items','sale.client','sale.salesman');


//
			if ($this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
				$this->filled
				('endDate')){
				
				$_startDate = Carbon::parse($this->startDate);
				$_endDate = Carbon::parse($this->endDate);
				
				$query = $query->whereDateBetween('created_at',[
					$_startDate->toDateString(),
					$_endDate->toDateString()
				]);
			}else{
				$query = $query->whereDate('created_at',Carbon::today());
			}
			
			
			if ($this->has('creators') && $this->filled('creators')){
				$query = $query->whereIn('creator_id',$this->input("creators"));
			}
			
			
			if ($this->has('clients') && $this->filled('clients')){
				$ids = SaleInvoice::whereIn('client_id',$this->input("clients"))->get()->pluck('invoice_id');
//				return $ids;
				$query = $query->whereIn('id',$ids);
			}
			
			
			if ($this->has('salesmen') && $this->filled('salesmen')){
				$ids = SaleInvoice::whereIn('salesman_id',$this->input("salesmen"))->get()->pluck('invoice_id');
				$query = $query->whereIn('id',$ids);
			}
			
			
			if ($this->has('title') && $this->filled('title')){
				
				$arr = explode("-",$this->input('title'));
				if (count($arr) >= 2){
					$number = $arr[1];
				}else{
					$number = $this->input('title');
				}
				
				
				$query = $query->where('id',$number);
			}

//
//
			if ($this->has('net') && $this->filled('net')){
				$amount = explode("-",$this->net);
				if (count($amount) >= 2){
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				}else{
					$startAmount = $this->net;
					$endAmount = $this->net;
				}
				$query = $query->whereBetween('net',[$startAmount,$endAmount]);
			}
			if ($this->has('tax') && $this->filled('tax')){
				$amount = explode("-",$this->tax);
				if (count($amount) >= 2){
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				}else{
					$startAmount = $this->tax;
					$endAmount = $this->tax;
				}
				$query = $query->whereBetween('tax',[$startAmount,$endAmount]);
			}
			if ($this->has('total') && $this->filled('total')){
				$amount = explode("-",$this->total);
				if (count($amount) >= 2){
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				}else{
					$startAmount = $this->total;
					$endAmount = $this->total;
				}
				$query = $query->whereBetween('total',[$startAmount,$endAmount]);
			}
			if ($this->has('discount') && $this->filled('discount')){
				$amount = explode("-",$this->discount);
				if (count($amount) >= 2){
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				}else{
					$startAmount = $this->discount;
					$endAmount = $this->discount;
				}
				$query = $query->whereBetween('discount',[$startAmount,$endAmount]);
			}
			if ($this->has('subtotal') && $this->filled('subtotal')){
				$amount = explode("-",$this->subtotal);
				if (count($amount) >= 2){
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				}else{
					$startAmount = $this->subtotal;
					$endAmount = $this->subtotal;
				}
				$query = $query->whereBetween('subtotal',[$startAmount,$endAmount]);
			}
			
			
			if ($this->has('current_status') && $this->filled('current_status')){
				if (in_array($this->input("current_status"),['credit','paid'])){
					$query = $query->where('current_status',$this->input("current_status"));
				}
				
			}
			
			if ($this->has('invoice_type') && $this->filled('invoice_type')){
				if (in_array($this->input("invoice_type"),['sale','r_sale'])){
					$query = $query->where('invoice_type',$this->input("invoice_type"));
				}
				
			}
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
