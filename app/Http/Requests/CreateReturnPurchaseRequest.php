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
			
			$all_qty = 0;
			foreach ($this->items as $item){
				$all_qty += $item['returned_qty'];
			}
			
			
			$invoice = null;
			DB::beginTransaction();
			try{
				
				
				if ($all_qty <= 0){
					return;
					//throw new \Exception('please add some return qty');
				}
				
				$data['total'] = 0;
				$data['remaining'] = 0;
				$data['net'] = 0;
				$data['subtotal'] = 0;
				$data['discount_value'] = 0;
				$data['discount_percent'] = 0;
				$data['organization_id'] = $this->user()->organization_id;
				$invoice = $purchase->invoice->createChildInvoice($data,'r_purchase');
				
				$return_invoice_data = $purchase->only('vendor_id','receiver_id');
				$return_invoice_data['prefix'] = 'RPU-';
				$return_invoice_data['invoice_type'] = 'r_purchase';
				$return_invoice_data['organization_id'] = auth()->user()->organization_id;
				$return_invoice_data['parent_id'] = $purchase->id;
				$return_purchase = $invoice->purchase()->create($return_invoice_data);
				
				$result = $invoice->createReturnItems($this->items,$return_purchase);
				$result['discount_percent'] = $result['discount_value'];
				$invoice->update($result);
				
				$purchase->invoice->makeInvoiceUpdatedOrDeleted();
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
	}
