<?php
	
	namespace App\Http\Requests\Sales;
	
	use App\Models\Invoice;
	use App\Models\Sale;
	use Carbon\Carbon;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class FetchSalesRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
			// return $this->user()->can('view sale');
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
		
		public function getData()
		{
			
			
			$query = Invoice::whereIn('invoice_type', ['sale', 'return_sale']);
			
			
			if($this->has('is_draft') && $this->filled('is_draft')) {
				$isDraft = (boolean)$this->input('is_draft');
				if($isDraft) {
					$query = $query->withoutGlobalScope('draft')->withoutGlobalScope('manager')->where('is_draft', $isDraft);
				}
			}
			
			
			$query = $query->with(
				[
					'creator', 'items', 'sale' => function($sale) {
					return $sale->withoutGlobalScope('draft')->withoutGlobalScope('manager');
					
				}, 'sale.client', 'sale.salesman',
				]
			);
			
			
			if(
				$this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
				$this->filled('endDate')
			) {
				$_startDate = Carbon::parse($this->input("startDate"));
				$_endDate = Carbon::parse($this->input("endDate"));
				
				if($_endDate === $_startDate) {
					$query = $query->whereDate('created_at', $_startDate);
				} else {
					$query = $query->whereBetween(
						'created_at', [
						$_startDate,
						$_endDate,
					]
					);
				}
			} else {
				if(!$this->user()->can('manage branches') && !$this->filled('title') && auth()->user()->accounts_closed_at != null) {
					
					$query = $query->where('created_at', '>=', Carbon::parse(auth()->user()->accounts_closed_at));
				}
			}
			
			if($this->has('creators') && $this->filled('creators')) {
				$query = $query->whereIn('creator_id', $this->input("creators"));
			}
			
			if($this->has('departments') && $this->filled('departments')) {
				$query = $query->whereIn('department_id', $this->input("departments"));
			}
			
			if($this->has('clients') && $this->filled('clients')) {
				$ids = Sale::whereIn('client_id', $this->input("clients"))->get()->pluck('invoice_id');
				$query = $query->whereIn('id', $ids);
			}
			
			if($this->has('salesmen') && $this->filled('salesmen')) {
				$ids = Sale::whereIn('salesman_id', $this->input("salesmen"))->get()->pluck('invoice_id');
				$query = $query->whereIn('id', $ids);
			}
			
			if($this->has('title') && $this->filled('title')) {
				
				$arr = explode("-", $this->input('title'));
				if(count($arr) >= 2) {
					$number = $arr[1];
				} else {
					$number = $this->input('title');
				}
				
				$query = $query->where('id', $number)->withoutGlobalScope('manager');
			}
			
			if($this->has('net') && $this->filled('net')) {
				$amount = explode("-", $this->net);
				if(count($amount) >= 2) {
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				} else {
					$startAmount = $this->net;
					$endAmount = $this->net;
				}
				$query = $query->whereBetween('net', [$startAmount, $endAmount]);
			}
			if($this->has('tax') && $this->filled('tax')) {
				$amount = explode("-", $this->tax);
				if(count($amount) >= 2) {
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				} else {
					$startAmount = $this->tax;
					$endAmount = $this->tax;
				}
				$query = $query->whereBetween('tax', [$startAmount, $endAmount]);
			}
			if($this->has('total') && $this->filled('total')) {
				$amount = explode("-", $this->total);
				if(count($amount) >= 2) {
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				} else {
					$startAmount = $this->total;
					$endAmount = $this->total;
				}
				$query = $query->whereBetween('total', [$startAmount, $endAmount]);
			}
			if($this->has('discount') && $this->filled('discount')) {
				$amount = explode("-", $this->discount);
				if(count($amount) >= 2) {
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				} else {
					$startAmount = $this->discount;
					$endAmount = $this->discount;
				}
				$query = $query->whereBetween('discount', [$startAmount, $endAmount]);
			}
			if($this->has('subtotal') && $this->filled('subtotal')) {
				$amount = explode("-", $this->subtotal);
				if(count($amount) >= 2) {
					$startAmount = $amount[0];
					$endAmount = $amount[1];
				} else {
					$startAmount = $this->subtotal;
					$endAmount = $this->subtotal;
				}
				$query = $query->whereBetween('subtotal', [$startAmount, $endAmount]);
			}
			
			if($this->has('current_status') && $this->filled('current_status')) {
				if(in_array($this->input("current_status"), ['credit', 'paid'])) {
					$query = $query->where('current_status', $this->input("current_status"));
				}
			}
			
			if($this->has('invoice_type') && $this->filled('invoice_type')) {
				if(in_array($this->input("invoice_type"), ['sale', 'return_sale'])) {
					$query = $query->where('invoice_type', $this->input("invoice_type"));
				}
			}
			
			if($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')) {
				$query = $query->orderBy($this->orderBy, $this->orderType);
			} else {
				// $query = $query->orderByDesc("id");
			}
			
			$query = $query->withCount(
				[
					'items AS invoice_cost' => function($query) {
						$query->select(DB::raw("SUM(cost * qty) as invoice_cost"));
					},
				]
			);
			
			
			if($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")) >= 1) {
				$result = $query->paginate(intval($this->input('itemsPerPage')));
			} else {
				$result = $query->paginate(50);
			}
			return $result;
		}
	}
