<?php
	
	namespace App\Http\Requests;
	
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;

// use Illumunate\Support\Facades\DB;
	class CreateSalesInvoiceRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			
			return $this->user()->isAuthorizedTo('create-sale-invoice');
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
				'salesman_id' => 'required|integer|exists:users,id',
				'creator_id' => 'required|integer|exists:managers,id',
				'client_id' => 'required|integer|exists:users,id',
				'department_id' => 'required|integer|exists:departments,id',
				'branch_id' => 'required|integer|exists:branches,id',
				'methods.*.id' => 'required|integer|exists:gateways,id',
				'items' => 'required|array',
				'items.*.id' => 'required|integer|exists:items,id',
				'items.*.price' => 'required|numeric|min:0',
				'items.*.total' => 'required|numeric',
				'items.*.tax' => 'required|numeric',
				'items.*.subtotal' => 'required|numeric',
				'items.*.net' => 'required|numeric',
				'items.*.discount' => 'required|numeric',
				'items.*.qty' => ['required','integer','min:1'],//,  new ItemQtyValidationRule($this->get('items'))
				'items.*.serials.*' => 'required|string|exists:item_serials,serial',
				'total' => 'required|numeric',
				'subtotal' => 'required|numeric',
				'discount_value' => 'required|numeric',
				'discount_percent' => 'required|numeric',
				'tax' => 'required|numeric',
				'net' => 'required|numeric',
				'remaining' => 'required|numeric'
			];
		}
		
		public function save()
		{
			
			$sale = null;
			DB::beginTransaction();
			try{
				$data = $this->except('items','salesman_id','client_id','methods','remaining','expenses');
				$data['remaining'] = $this->remaining>=0 ? $this->remaining : 0;
				
				$data['invoice_type'] = 'sale';
				
				
				$invoice = auth()->user()->organization->invoices()->create($data);
				
				
				$sale = $invoice->sale()->create([
					'organization_id' => $this->user()->organization_id,
					'salesman_id' => $this->salesman_id,
					'client_id' => $this->client_id,
					'is_full_returned' => 0,
					'is_returned' => 0,
					'invoice_type' => 'sale',
					'prefix' => 'SAI-',
					'parent_id' => 0
				]);
				
				
				$expenses_data = $this->get_expenses_array();
				$invoice->createChildrenItems($this->items,$this->client_id,$sale,'sale',$expenses_data);
				
				
				//$invoice->add_expenses_to_invoice($expenses_data);
				$methods = $this->methods;
				$methods[0]['amount'] = $this->remaining < 0 ? ($methods[0]['amount'] - abs($this->remaining)) :
					$methods[0]['amount'];
				$paid = !empty($methods) ? $invoice->createPayments($this->client_id,$methods,$invoice,'receipt') : 0;
				$status = $this->net <= $paid ? 'paid' : 'credit';
				$invoice->setCreationStatusAs($status)->setTypeAs('sale');
				DB::commit();
			}catch (\Exception $e){
				DB::rollBack();
				throw new \Exception($e->getMessage());
			}
			
			
			return $sale;
			
			
		}
		
		
		
		public function get_expenses_array()
		{
			$expenses_data = [];
			if(!empty($this->expenses))
			{
				foreach ($this->expenses as $expens)
				{
					if($expens['is_open'] && $expens['amount'] > 0)
					{
						$expenses_data[] = $expens;
					}
				}
			}
			
			return $expenses_data;
		}
		
	}
