<?php
	
	namespace App\Http\Requests;
	
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateReturnPurchaseRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
			return $this->user()->isAuthorizedTo('return-purchase');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'methods.*.id' => 'required|integer|exists:accounts,id',
				'items' => 'required|array',
				'items.*.id' => 'required|integer|exists:invoice_items,id',
				'items.*.price' => 'required|numeric|min:0',
				'items.*.total' => 'required|numeric',
				'items.*.tax' => 'required|numeric',
				'items.*.subtotal' => 'required|numeric',
				'items.*.net' => 'required|numeric',
				'items.*.discount' => 'required|numeric',
				'items.*.qty' => 'required|integer|min:1',
				'items.*.serials.*.serial' => 'required|exists:item_serials,serial'
			
			
			];
		}
		
		public function save($purchase)
		{
			
			$invoice = null;
			DB::beginTransaction();
			try{
				
				
				$this->validate_request_has_returned_items();
				
				$invoice = $purchase->invoice->make_instance_child_invoice('r_purchase');
				$return_invoice_data = $purchase->only('vendor_id','receiver_id');
				$return_invoice_data['prefix'] = 'RPU-';
				$return_invoice_data['invoice_type'] = 'r_purchase';
				$return_invoice_data['organization_id'] = auth()->user()->organization_id;
				$return_invoice_data['parent_id'] = $purchase->id;
				$invoice->purchase()->create($return_invoice_data);
				$invoice->create_return_invoice_items($this->items,$invoice);
				$data = $invoice->update_return_invoice_data();

//				dd(($this->methods));
				$expenses = [];
				$invoice_status = $invoice->handle_invoice_transactions($this->methods,$invoice->vendor_id,$data['net'],
					$this->items,$expenses,$invoice_type = 'r_purchase');

//				return $expenses;
				$invoice->update_invoice_creation_status($invoice_status);
				
				
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
		
//		public function ()
//		{
//
//		}
		
		
		private function validate_request_has_returned_items()
		{
			
			$has_returned_qty = false;
			foreach ($this->items as $item){
				if ($item['returned_qty'] >= 0){
					$has_returned_qty = true;
				}
			}
//
			if ($has_returned_qty == false)
				throw  Exception();
		}
	}
