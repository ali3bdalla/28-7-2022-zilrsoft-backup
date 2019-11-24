<?php
	
	namespace App\Http\Requests;
	
	use App\Http\Requests\Invoice\PurchaseCreationRequest;
	use App\ItemSerials;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateSalesInvoiceRequest extends FormRequest
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
				'salesman_id' => 'required|integer|exists:users,id',
				'client_id' => 'required|integer|exists:users,id',
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
				'items.*.price' => 'required|numeric',
				'items.*.qty' => ['required','integer','min:1'],
				'items.*.serials.*' => ['required',function ($attr,$value,$fail){
					$serial = ItemSerials::where('serial',$value)->first();
					if (!empty($serial)){
						if (!in_array($serial->current_status,['available','r_sale'])){
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
				'remaining' => 'required|numeric',
//
			];
		}
		
		public function save()
		{
			
			DB::beginTransaction();
			try{
				
				$invoice = $this->create_invoice();
				$sub_invoice = $this->create_subinvoice($invoice);
				$children_purchases = $this->make_purchase_invoices_for_expenses_items();
				$invoice->add_items_to_invoice($this->items,$sub_invoice,[],'sale',$this->client_id);

//				dd($invoice->items);
				$invoice_status = $invoice->handle_invoice_transactions($this->methods,$this->client_id,
					$this->net,$this->items,[],'sale');
				
				
				$invoice->update_invoice_creation_status($invoice_status);
				
				
				foreach ($children_purchases as $child){
					$child->update([
						'parent_invoice_id' => $invoice->id
					]);
				}
				DB::commit();
				return [
					'invoice' => $invoice,
					'sub_invoice' => $sub_invoice,
				];
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}


//			return true;
			
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
			$data['invoice_type'] = 'sale';
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
			return $invoice->sale()->create([
				'organization_id' => $this->user()->organization_id,
				'client_id' => $this->client_id,
				'salesman_id' => $this->salesman_id,
				'is_full_returned' => false,
				'invoice_type' => 'sale',
				'is_returned' => false,
				'prefix' => 'SAI-',
				'parent_id' => 0
			
			]);
		}
		
		public function make_purchase_invoices_for_expenses_items()
		{
			$invoices = [];
			foreach ($this->items as $item){
				if ($item['is_expense'])
					$invoices[] = $this->make_single_expense_purchase($item);
			}
			
			return $invoices;
			
		}
		
		public function make_single_expense_purchase($item)
		{
//
			
			$purchase = new PurchaseCreationRequest();
			$item['qty'] = 1;
			$item['total'] = $item['purchase_price'];
			$item['subtotal'] = $item['total'];
			$item['tax'] = $item['vtp'] * $item['subtotal'] / 100;
			$item['net'] = $item['tax'] + $item['subtotal'];
			
			$item['cost'] = $item['purchase_price'];
			
			
			$data['total'] = $item['total'];
			$data['subtotal'] = $item['subtotal'];
			$data['tax'] = $item['tax'];
			$data['net'] = $item['net'];
			$data['discount_percent'] = $item['discount'];
			$data['discount_value'] = $item['discount'];
			
			$gateway = auth()->user()->manager_gateway('cash');
			$gateway->amount = $item['net'];
			$gateway->is_paid = true;
			
			$invoice = $purchase->create_invoice($data,auth()->user());
			$sub_invoice = $purchase->create_subinvoice($invoice,auth()->user(),auth()->user()->id,$item['expense_vendor_id'],'0000');
			
			
			$invoice->add_items_to_invoice([$item],$sub_invoice,[],'purchase',$item['expense_vendor_id']);
			
			$invoice_status = $invoice->handle_invoice_transactions([$gateway],$item['expense_vendor_id'],
				$item['net'],[$item],[]);
			
			$invoice->update_invoice_creation_status($invoice_status);

//			dd($invoice->items);
			
			return $invoice;
		}

//
	}
