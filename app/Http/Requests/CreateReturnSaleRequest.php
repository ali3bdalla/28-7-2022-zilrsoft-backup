<?php
	
	namespace App\Http\Requests;
	
	use App\Models\Accounting\AmountsAccounting;
	use App\Models\Accounting\ExpensesAccounting;
	use App\Models\Accounting\IdentityAccounting;
	use App\Models\Accounting\PaymentAccounting;
	use App\Models\Accounting\TransactionAccounting;
	use App\Models\Invoice;
	use Dotenv\Exception\ValidationException;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateReturnSaleRequest extends FormRequest
	{
		use TransactionAccounting,PaymentAccounting,IdentityAccounting,ExpensesAccounting,AmountsAccounting;
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
				"items" => "required|array",
				"items.*.id" => "required|integer|exists:invoice_items,id",
				"items.*.returned_qty" => "required|integer",
				'methods.*.id' => 'required|integer|exists:accounts,id',
			];
		}
		
		public function save($invoice)
		{
			
			DB::beginTransaction();
			try{
//				$items = $this->input("items");
//				$base_invoice =
//					Invoice::initEmptyInvoice('r_sale',$invoice)
//						->addChildInvoice($invoice->sale->client_id,
//							"r_sale",$invoice->sale->salesman_id)
//						->addReturnedItemsToBaseInvoice($items)
//						->updateBaseInvoiceAccountingInformation()
//						->createInvoiceTransactions($this->input("methods"),'r_sale');
//				DB::commit();
//
				$createdInvoice = Invoice::publish(['invoice_type' => 'r_sale','notes' => "",'parent_id' =>
					$invoice->id]);
				$sale = $createdInvoice->publishSubInvoice('sale',[
					'invoice_type' => 'r_sale',
					'prefix' => 'RSI-',
					'client_id' => $invoice->sale->client_id,
					'alice_name' => $invoice->sale->alice_name,
					'salesman_id' => $invoice->sale->salesman_id]);
//				$this->toCreatePurchaseInvoiceForExpensesItems($invoice,$this->input('items'));
				$createdInvoice->addReturnedItemsToBaseInvoice($this->input('items'));
				$this->toGetAndUpdatedAmounts($createdInvoice);
				$this->toCreateInvoiceTransactions($createdInvoice,$this->input('items'),$this->input("methods"),[]);
				
				
				DB::commit();
				
				return $createdInvoice->fresh();
			}catch (Exception $exception){
				DB::rollBack();
				return response(json_encode([
					'message' => $exception->getMessage()
				]),400);
			}
			
			
		}
	}
