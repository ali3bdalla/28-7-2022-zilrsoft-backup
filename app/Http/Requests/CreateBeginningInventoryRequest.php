<?php
	
	namespace App\Http\Requests;
	
	use App\Account;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateBeginningInventoryRequest extends FormRequest
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
				'receiver_id' => 'required|integer|exists:users,id',
				'creator_id' => 'required|integer|exists:managers,id',
				'vendor_id' => 'required|integer|exists:users,id',
				'department_id' => 'required|integer|exists:departments,id',
				'branch_id' => 'required|integer|exists:branches,id',
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
//            'items.*.serials','required_if:items.*.is_need_serial,=,true|array|min:1',
//            'items.*.serials','required|array|min:1',
				'items.*.serials.*' => 'required|unique:item_serials,serial|min:2',
				'total' => 'required|numeric',
				'subtotal' => 'required|numeric',
				'discount_value' => 'required|numeric',
				'discount_percent' => 'required|numeric',
				'tax' => 'required|numeric',
				'net' => 'required|numeric',
				'vendor_inc_number' => 'required|string',
				'remaining' => 'required|numeric|min:0'
			];
		}
		
		public function save()
		{
			
			
			DB::beginTransaction();
			try{
				
				
				$invoice = $this->create_invoice();
				$sub_invoice = $this->create_subinvoice($invoice);
				$invoice->add_items_to_beginning_inventory($this->items,$this->vendor_id,$sub_invoice,'beginning_inventory');
				
				$expenses = [];
				$account = Account::where('slug','equity')->latest()->first();
				$account->amount = $this->net;
				$methods = [
					$account
				];
				$invoice_status = $invoice->handle_invoice_transactions($methods,$this->vendor_id,$this->net,$this->items,$expenses);
				
				$invoice->setCreationStatusAs('paid')->setTypeAs('beginning_inventory');
//				$invoice->create_invoice_entry($inventory);
				DB::commit();
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
			$data['invoice_type'] = 'beginning_inventory';
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
				'is_full_returned' => 0,
				'invoice_type' => 'beginning_inventory',
				'is_returned' => 0,
				'vendor_inc_number' => $this->vendor_inc_number,
				'prefix' => 'BGN-',
				'parent_id' => 0
			
			]);
		}
		
	}
