<?php
	
	namespace App\Http\Requests;
	
	use Dotenv\Exception\ValidationException;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateReturnSaleRequest extends FormRequest
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
//				'items.*.id' => 'required|integer|exists:invoice_items,id',
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
		
		public function save($sale)
		{
			
			$invoice = null;
			DB::beginTransaction();
			try{
				
				$this->validate_request_has_returned_items();
				$invoice = $sale->invoice->make_instance_child_invoice('r_sale');
				$this->create_sale_subinvoice($invoice,$sale);
				$items = $this->items;
				$invoice->create_return_invoice_items($items,$invoice);

//				$sale->invoice->update_return_invoice_data();
//				$invoice->update_invoice_totals_data();
//				$expenses = [];
//				$invoice_status = $invoice->handle_invoice_transactions($this->methods,$invoice->sale->client_id,
//					$invoice->net,
//					$invoice->items()->where('belong_to_kit',false)->get(),$expenses,$invoice_type = 'r_sale');
//
//				$invoice->update_invoice_creation_status($invoice_status);
				DB::commit();
				return $invoice;
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
		
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
				throw  new ValidationException([
					'items.0.returned_qty' => 'put some data'
				]);
		}
		
		public function create_sale_subinvoice($invoice,$sale)
		{
			$return_invoice_data = $sale->only('salesman_id','client_id');
			$return_invoice_data['prefix'] = 'RSA-';
			$return_invoice_data['invoice_type'] = 'r_sale';
			$return_invoice_data['organization_id'] = auth()->user()->organization_id;
			$return_invoice_data['parent_id'] = $sale->id;
			return $invoice->sale()->create($return_invoice_data);
		}
		
	}
