<?php
	
	namespace App\Http\Requests;
	
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateQuotationRequest extends FormRequest
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
				$invoice->add_items_to_invoice($this->items,$sub_invoice,[],'quotation',$this->client_id);
				
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
		 * @toCreate CoreInvoice
		 */
		public function create_invoice()
		{
			$data = $this->only('total','subtotal','remaining','net','tax','discount_value',
				'discount_percent');
			$data['creator_id'] = $this->user()->id;
			$data['department_id'] = $this->user()->department_id;
			$data['branch_id'] = $this->user()->branch_id;
			$data['invoice_type'] = 'quotation';
			$invoice = $this->user()->organization->invoices()->create($data);
			return $invoice;
		}
		
		/**
		 *
		 *
		 * @toCreate Sub CoreInvoice
		 */
		public function create_subinvoice($invoice)
		{
			return $invoice->sale()->create([
				'organization_id' => $this->user()->organization_id,
				'client_id' => $this->client_id,
				'salesman_id' => $this->salesman_id,
				'is_full_returned' => false,
				'invoice_type' => 'quotation',
				'is_returned' => false,
				'prefix' => 'QOT-',
				'parent_id' => 0
			
			]);
		}

//
	}
