<?php
	
	namespace App\Http\Requests\Accounting\Sale;
	
	use App\Invoice;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateSaleRequest extends FormRequest
	{
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
				'methods.*.id' => 'required|integer|exists:accounts,id',
			
			];
		}
		
		public function save()
		{
			
			DB::beginTransaction();
			try{
				$items = $this->items;
				$base_invoice =
					Invoice::initEmptyInvoice('sale',null,$this->input('notes'))
						->addChildInvoice($this->client_id,"sale",$this->salesman_id)
						->createExpensesPurchases($items)
						->addItemsToBaseInvoice($items)
						->updateBaseInvoiceAccountingInformation()
						->createInvoiceTransactions($this->methods);
//
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
