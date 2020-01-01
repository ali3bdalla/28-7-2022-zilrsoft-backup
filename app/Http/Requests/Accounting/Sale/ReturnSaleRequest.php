<?php
	
	namespace App\Http\Requests\Accounting\Sale;
	
	use App\Invoice;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class ReturnSaleRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit sale');
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
					'invoice_type' => 'r_sale',
					'parent_id' => $baseInvoice->id
				]);
				$return_sale = $invoice->publishSubInvoice('sale',[
					'invoice_type' => 'r_sale',
					'prefix' => 'RSI-',
					'client_id' => $baseInvoice->sale->client_id,
					'salesman_id' => $baseInvoice->sale->salesman_id
				]);
				$items = $invoice->pushItems($this->items);
				$invoice = $invoice->runAccountingAmountUpdater();
				$invoice->pushTransactions($this->input("methods"),'r_sale');
				$baseInvoice->updateReturnStatus();
				
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
