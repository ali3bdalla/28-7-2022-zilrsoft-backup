<?php
	
	namespace App\Http\Requests\Accounting\Sale;
	
	use App\Accounting\AmountsAccounting;
	use App\Accounting\ExpensesAccounting;
	use App\Accounting\IdentityAccounting;
	use App\Accounting\PaymentAccounting;
	use App\Accounting\TransactionAccounting;
	use App\Invoice;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateSaleRequest extends FormRequest
	{
		
		use TransactionAccounting,PaymentAccounting,IdentityAccounting,ExpensesAccounting,AmountsAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create sale');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				"notes" => "nullable|string",
				"items" => "required|array",
				"items.*.id" => "required|integer|exists:items,id",
				"items.*.price" => "validate_item_price_or_discount|price",
				"items.*.purchase_price" => "validate_item_purchase_price|price",
				"items.*.discount" => "validate_item_price_or_discount",
				"items.*.qty" => "required|integer|item_has_available_qty:items.*.id",
				"items.*.expense_vendor_id" => "validate_expense_vendor",
				"items.*.serials.*" => 'required|validate_item_serials',
				"items.*.serials" => "validate_serials_array|array",
				"client_id" => "required|integer|exists:users,id",
				"salesman_id" => "required|integer|exists:managers,id",
				"alice_name" => "nullable|string",
				'methods.*.id' => 'required|integer|exists:accounts,id',
			
			];
		}
		
		public function save()
		{
			
			DB::beginTransaction();
			try{
				$invoice = Invoice::publish(['invoice_type' => 'sale','notes' => $this->input("notes"),'parent_id' => 0]);
				$sale = $invoice->publishSubInvoice('sale',[
					'invoice_type' => 'sale',
					'prefix' => 'SAI-',
					'client_id' => $this->input("client_id"),
					'alice_name' => $this->input("alice_name"),
					'salesman_id' => $this->input("salesman_id")]);
				$this->toCreatePurchaseInvoiceForExpensesItems($invoice,$this->input('items'));
				$invoice->addItemsToBaseInvoice($this->input('items'));
				$this->toGetAndUpdatedAmounts($invoice);
				$this->toCreateInvoiceTransactions($invoice,$this->input('items'),$this->input("methods"),[]);
				$this->deleteQuotationAfterCloneIt();
				DB::commit();
				return $invoice->fresh();
			}catch (Exception $exception){
				DB::rollBack();
				return response(json_encode([
					'message' => $exception->getMessage()
				]),400);
			}
			
			
		}
		
		/**
		 *
		 */
		public function deleteQuotationAfterCloneIt()
		{
			if ($this->filled('quotation_id') && $this->has("quotation_id")){
				if ($this->input("quotation_id") > 0){
					$quotation = Invoice::find($this->input("quotation_id"));
					if (!empty($quotation)){
						$quotation->delete();
					}
				}
			}
			
		}
	}
	
	
	
	
	
	
	//				Invoice::
	//				initEmptyInvoice('sale',null,$this->input('notes'))
	//					->addChildInvoice($this->input("client_id"),
	//						"sale",$this->salesman_id,
	//						null,
	//						$this->input("alice_name"))
	//					->createExpensesPurchases($items)
	//					->addItemsToBaseInvoice($items)
	//					->updateBaseInvoiceAccountingInformation()
	//					->createInvoiceTransactions($this->methods);
	//				DB::commit();
