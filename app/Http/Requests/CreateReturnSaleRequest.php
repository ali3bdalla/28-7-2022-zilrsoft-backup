<?php
	
	namespace App\Http\Requests;
	
	use App\Invoice;
	use Dotenv\Exception\ValidationException;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateReturnSaleRequest extends FormRequest
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
				$items = $this->input("items");
				$base_invoice =
					Invoice::initEmptyInvoice('r_sale',$invoice)
						->addChildInvoice($invoice->sale->client_id,
							"r_sale",$invoice->sale->salesman_id)
						->addReturnedItemsToBaseInvoice($items)
						->updateBaseInvoiceAccountingInformation()
						->createInvoiceTransactions($this->input("methods"),'r_sale');
				DB::commit();
				return $base_invoice;
			}catch (Exception $exception){
				DB::rollBack();
				return response(json_encode([
					'message' => $exception->getMessage()
				]),400);
			}
			
			
		}
	}
