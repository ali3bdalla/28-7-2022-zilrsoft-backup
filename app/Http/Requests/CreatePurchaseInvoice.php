<?php
	
	namespace App\Http\Requests;
	
	use App\ItemSerials;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreatePurchaseInvoice extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return auth()->user()->isAuthorizedTo('create-purchase-invoice');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'receiver_id' => 'required|integer|exists:users,id',
				'creator_id' => 'required|integer|exists:managers,id',
				'vendor_id' => 'required|integer|exists:users,id',
				'department_id' => 'required|integer|exists:departments,id',
				'branch_id' => 'required|integer|exists:branches,id',
				'methods.*.id' => 'required|integer|exists:gateways,id',
				'items' => 'required|array',
				'items.*.id' => ['required','integer','exists:items,id'],
				'items.*.price' => 'required|numeric|min:0|',
				'items.*.total' => 'required|numeric',
				'items.*.tax' => 'required|numeric',
				'items.*.subtotal' => 'required|numeric',
				'items.*.net' => 'required|numeric',
				'items.*.discount' => 'required|numeric',
				'items.*.qty' => 'required|integer|min:1',
				'items.*.purchase_price' => 'required|numeric',
				'items.*.price_with_tax' => 'required|numeric|min:0',

				'items.*.serials.*' => ['required',function ($attr,$value,$fail){
					$serial = ItemSerials::where('serial',$value)->first();
					if (!empty($serial)){
						if (in_array($serial->current_status,['saled','available','r_sale'])){
							$fail('this serial is already exists');
						}
					}
				}],
				'total' => 'required|numeric',
				'subtotal' => 'required|numeric',
				'discount_value' => 'required|numeric',
				'discount_percent' => 'required|numeric',
				'tax' => 'required|numeric',
				'net' => 'required|numeric',
				'vendor_inc_number' => 'required|string',
				'remaining' => 'required|numeric'
			];
		}
		
		public function save()
		{
			$purchase = null;
			DB::beginTransaction();
			try{
				$user = $this->user();
				$data = $this->except('items','receiver_id','vendor_id','methods','vendor_inc_number','document','remaining','expenses');
				$data['remaining'] = $this->remaining>=0 ? $this->remaining : 0;
				$invoice = $user->organization->invoices()->create($data);
				$purchase = $invoice->purchase()->create([
					'organization_id' => $this->user()->organization_id,
					'receiver_id' => $this->receiver_id,
					'vendor_id' => $this->vendor_id,
					'is_full_returned' => false,
					'invoice_type' => 'purchase',
					'is_returned' => false,
					'vendor_inc_number' => $this->vendor_inc_number,
					'prefix' => 'PUI-',
					'parent_id' => 0

				]);
				
				
				$expenses_data = $this->get_expenses_array();
				$invoice->createChildrenItems($this->items,$this->vendor_id,$purchase,"purchase",$expenses_data);
				
				
				
				$methods = $this->methods;
				$methods[0]['amount'] = $this->remaining < 0 ? ($methods[0]['amount'] - abs($this->remaining)) :
					$methods[0]['amount'];
				
				
				
				$paid = !empty($methods) ? $invoice->createPayments($this->vendor_id,$methods,$invoice,'payment') : 0;
				
				
				$status = $this->net <= $paid ? 'paid' : 'credit';
				
				$invoice->setCreationStatusAs($status);
				DB::commit();
			}catch (\Exception $e){
				DB::rollBack();
				throw new \Exception($e->getMessage());
			}
			
			
			return $purchase;
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
