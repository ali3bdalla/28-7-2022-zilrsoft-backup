<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Accounting\AmountsAccounting;
	use App\Accounting\CostAccounting;
	use App\Accounting\ExpensesAccounting;
	use App\Accounting\QtyTransactionAccounting;
	use App\Accounting\TransactionAccounting;
	use App\Transaction;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Http\RedirectResponse;
	
	class ActivateAdjustStockRequest extends FormRequest
	{
		
		use TransactionAccounting,ExpensesAccounting,AmountsAccounting,QtyTransactionAccounting,CostAccounting;
		
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
				//
			];
		}
		
		/**
		 * @param $adjust_stock
		 *
		 * @return RedirectResponse
		 */
		public function activate($adjust_stock)
		{
			$adjust_stock->update([
				'is_deleted' => false
			]);
			
			$adjust_stock->items()->withoutGlobalScope("pendingItemsScope")->update([
				'is_pending' => false,
				
			]);
			
			
			foreach ($adjust_stock->items as $incItem){
				$this->toUpdateItemAvailableQty($incItem->item,$incItem->qty,"set");
				$this->toUpdateCostAfterInvoiceCreated($incItem->item,$incItem);
			}
			
			Transaction::where([
				'invoice_id' => $adjust_stock->id
			])->withoutGlobalScope("pendingTransactionScope")->update([
				'is_pending' => false
			]);
			
			return back();
//			return 1;
		}
	}
