<?php
	
	namespace App\Http\Requests\Accounting\Purchase;
	
	use App\Accounting\AmountsAccounting;
	use App\Accounting\ExpensesAccounting;
	use App\Accounting\IdentityAccounting;
	use App\Accounting\PaymentAccounting;
	use App\Accounting\TransactionAccounting;
	use App\Invoice;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class ReturnPurchaseRequest extends FormRequest
	{
		use TransactionAccounting,PaymentAccounting,IdentityAccounting,ExpensesAccounting,AmountsAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit purchase');
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
				'items' => 'required|array',
				'items.*.id' => 'integer|required|exists:invoice_items,id',
				'items.*.returned_qty' => 'required',
				'methods.*.id' => 'integer|required|exists:accounts,id',
			
			];
		}
		
		public function makeReturn($baseInvoice)
		{
			DB::beginTransaction();
			try{
				$invoice = Invoice::publish([
					'invoice_type' => 'r_purchase',
					'parent_id' => $baseInvoice->id
				]);
				$return_sale = $invoice->publishSubInvoice('sale',[
					'invoice_type' => 'r_sale',
					'prefix' => 'RPU-',
					'salesman_id' => $baseInvoice->purchase->salesman_id,
					'vendor_id' => $baseInvoice->purchase->vendor_id
				]);
				$this->toCreatePurchaseInvoiceForExpensesItems($invoice,$this->input('items'));
				$invoice->pushItems($this->input('items'));
				$this->toGetAndUpdatedAmounts($invoice);
				$this->toCreateInvoiceTransactions($invoice,$this->input('items'),$this->input("methods"),[]);
				$this->toUpdateIsDeletedAndIsUpdated($baseInvoice);
				DB::commit();
				
				return $invoice;
			}catch (Exception $exception){
				
				DB::rollBack();
				
				return response(json_encode([
					'message' => $exception->getMessage()
				]),400);
			}
		}
	}