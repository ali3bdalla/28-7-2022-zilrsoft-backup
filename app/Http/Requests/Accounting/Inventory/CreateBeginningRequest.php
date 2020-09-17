<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Models\User;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateBeginningRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage inventory');
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
				'items.*.id' => ['required','integer','exists:items,id'],
				'items.*.price' => 'required|numeric|min:0|',
				'items.*.total' => 'required|numeric',
				'items.*.tax' => 'required|numeric',
				'items.*.subtotal' => 'required|numeric',
				'items.*.net' => 'required|numeric',
				'items.*.discount' => 'required|numeric',
				'items.*.qty' => 'required|integer|min:1',
				'items.*.purchase_price' => 'required|numeric',
				'items.*.serials.*' => 'required|unique:item_serials,serial|min:2',
				'total' => 'required|numeric',
				'subtotal' => 'required|numeric',
				'discount_value' => 'required|numeric',
				'discount_percent' => 'required|numeric',
				'tax' => 'required|numeric',
				'net' => 'required|numeric',
			];
		}
		
		public function save()
		{
			
			
			DB::beginTransaction();
			try{
				
				$user = User::where([
					['user_slug','beginning-inventory'],
					['is_system_user',true]
				])->first();
				
				
				$invoice = $this->create_invoice();
				$sub_invoice = $this->create_subinvoice($invoice,$user);
				$invoice->add_items_to_beginning_inventory($this->items,$user->id,$sub_invoice,'beginning_inventory');
				
				$expenses = [];
				$account = auth()->user()->get_active_manager_account_for("withdrawals");
				$account->amount = $this->net;
				$methods = [
					$account
				]; 
				$invoice_status = $invoice->handle_invoice_transactions($methods,$user->id,$this->net,
					$this->items,$expenses);
				
				$invoice->setCreationStatusAs('paid')->setTypeAs('beginning_inventory');
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
		
		/**
		 *
		 *
		 * @toCreate CoreInvoice
		 */
		public function create_invoice()
		{
			
			$data = $this->only('total','subtotal','remaining','net','tax','discount_value','discount_percent');
			$data['discount_value'] = 0;
			$data['discount_percent'] = 0;
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
		 * @toCreate Sub CoreInvoice
		 */
		public function create_subinvoice($invoice,$user)
		{
			
			
			return $invoice->purchase()->create([
				'organization_id' => $this->user()->organization_id,
				'receiver_id' => $this->user()->id,
				'vendor_id' => $user->id,
				'is_full_returned' => 0,
				'invoice_type' => 'beginning_inventory',
				'is_returned' => 0,
				'vendor_inc_number' => '000000000',
				'prefix' => 'BGN-',
				'parent_id' => 0
			
			]);
		}
		
	}
