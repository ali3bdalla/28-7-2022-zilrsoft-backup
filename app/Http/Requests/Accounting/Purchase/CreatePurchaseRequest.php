<?php
	
	namespace App\Http\Requests\Accounting\Purchase;
	
	use App\Accounting\AmountsAccounting;
	use App\Accounting\ExpensesAccounting;
	use App\Accounting\IdentityAccounting;
	use App\Accounting\PaymentAccounting;
	use App\Accounting\TransactionAccounting;
	use App\Invoice;
	use App\ItemSerials;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreatePurchaseRequest extends FormRequest
	{
		use TransactionAccounting,PaymentAccounting,IdentityAccounting,ExpensesAccounting,AmountsAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create purchase');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'receiver_id' => 'required|integer|exists:managers,id',
				'vendor_id' => 'required|integer|exists:users,id',
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
				'vendor_inc_number' => 'required|string',
				'remaining' => 'required|numeric',
				'expenses.*.id' => 'integer|required|exists:expenses',
				'expenses.*.is_open' => 'boolean|required',
				'expenses.*.is_apended_to_net' => 'boolean|required',
			];
		}
		
		public function save()
		{
			DB::beginTransaction();
			try{
				$invoice = Invoice::publish(['invoice_type' => 'purchase','parent_id' => 0]);
				$purchase = $invoice->publishSubInvoice('purchase',[
					'invoice_type' => 'purchase',
					'prefix' => 'PUI-',
					'vendor_id' => $this->input("vendor_id"),
					'vendor_inc_number' => $this->input("vendor_inc_number"),
					'receiver_id' => $this->input("receiver_id")]);
				$expenses = $this->toExtractExpenses();
				$invoice->add_items_to_invoice($this->items,$purchase,$expenses,'purchase',$this->input("vendor_id"));
				$this->toGetAndUpdatedAmounts($invoice);
				$this->toCreateInvoiceTransactions($invoice,$this->items,$this->methods,$expenses);
				DB::commit();
				return $invoice;
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
		
		public function toExtractExpenses()
		{
			$expensesArray = [];
			if (!empty($this->has('expenses') && $this->filled("expenses"))){
				foreach ($this->input("expenses") as $expense){
					if ($expense['amount'] > 0){
						$expensesArray[] = $expense;
					}
				}
			}
			return $expensesArray;
		}
	}
