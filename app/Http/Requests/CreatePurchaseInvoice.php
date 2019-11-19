<?php
	
	namespace App\Http\Requests\Invoice;
	
	use App\ItemSerials;
	use Exception;
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
				'receiver_id' => 'required|integer|exists:users,id',
				'vendor_id' => 'required|integer|exists:users,id',
				'department_id' => 'required|integer|exists:departments,id',
				'branch_id' => 'required|integer|exists:branches,id',
				'methods.*.id' => 'required|integer|exists:accounts,id',
				
				
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
				'remaining' => 'required|numeric',
//				'status' => 'required'
			];
		}
		
		public function save()
		{
			
			DB::beginTransaction();
			try{
				
				$invoice = $this->create_invoice();
				$sub_invoice = $this->create_subinvoice($invoice);
				$expenses = $this->get_expense_array();
				$invoice->add_items_to_invoice($this->items,$sub_invoice,$expenses,'purchase',$this->vendor_id);
				
				$expenses = $this->get_expense_array();
				$invoice_status = $invoice->handle_invoice_transactions($this->methods,$this->vendor_id,
					$this->net,$this->items,$expenses);

//				return $expenses;
				$invoice->update_invoice_creation_status($invoice_status);
				DB::commit();
				return [
					'invoice' => $invoice,
					'sub_invoice' => $sub_invoice,
				];
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
		
		/**
		 *
		 *
		 * @toCreate Invoice
		 */
		public function create_invoice()
		{
			$data = $this->only('total','subtotal','remaining','net','tax','discount_value',
				'discount_percent');
			$data['creator_id'] = $this->user()->id;
			$data['department_id'] = $this->user()->department_id;
			$data['branch_id'] = $this->user()->branch_id;
			$data['invoice_type'] = 'purchase';
			$invoice = $this->user()->organization->invoices()->create($data);
			return $invoice;
		}
		
		/**
		 *
		 *
		 * @toCreate Sub Invoice
		 */
		public function create_subinvoice($invoice)
		{
			return $invoice->purchase()->create([
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
		}
		
		public function get_expense_array()
		{
			$expenses_data = [];
			if (!empty($this->expenses)){
				foreach ($this->expenses as $expens){
					if ($expens['is_open'] && $expens['amount'] > 0){
						$expenses_data[] = $expens;
					}
				}
			}
			
			return $expenses_data;
		}
	}
