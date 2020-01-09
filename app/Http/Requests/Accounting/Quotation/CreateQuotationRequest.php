<?php
	
	namespace App\Http\Requests\Accounting\Quotation;
	
	use App\Accounting\AmountsAccounting;
	use App\Invoice;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateQuotationRequest extends FormRequest
	{
		
		use AmountsAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage quotation');
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
				"items.*.discount" => "numeric",
				"items.*.qty" => "required|integer",
				"client_id" => "required|integer|exists:users,id",
				"salesman_id" => "required|integer|exists:managers,id",
			
			];
		}
		
		public function save()
		{
			
			DB::beginTransaction();
			try{
				
				$invoice = Invoice::publish(['invoice_type' => 'quotation','parent_id' => 0]);
				$sale = $invoice->publishSubInvoice('sale',[
					'invoice_type' => 'quotation',
					'prefix' => 'QUI-',
					'client_id' => $this->input("client_id"),
					'salesman_id' => $this->input("salesman_id")]);
				$invoice->addItemsToBaseInvoice($this->input('items'));
				$this->toGetAndUpdatedAmounts($invoice);
				DB::commit();
				return $invoice->fresh();
			}catch (Exception $exception){
				
				DB::rollBack();
				return response(json_encode([
					'message' => $exception->getMessage()
				]),400);
			}
			
			
		}
		
	}
