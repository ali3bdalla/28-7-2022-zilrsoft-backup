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
			return $this->user()->isAuthorizedTo('return-purchase');
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
		
		public function save($sale)
		{
			
			DB::beginTransaction();
			try{
				$items = $this->items;
				$base_invoice =
					Invoice::initEmptyInvoice('r_sale',$sale->invoice)
						->addChildInvoice($sale->client_id,"r_sale",$sale->invoice)
						->addReturnedItemsToBaseInvoice($items)
						->updateBaseInvoiceAccountingInformation()
						->createInvoiceTransactions($this->methods,'r_sale');
				
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
