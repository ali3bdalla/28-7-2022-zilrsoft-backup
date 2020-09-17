<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Models\Accounting\AmountsAccounting;
	use App\Models\Accounting\CostAccounting;
	use App\Models\Accounting\ExpensesAccounting;
	use App\Models\Accounting\QtyTransactionAccounting;
	use App\Models\Accounting\TransactionAccounting;
	use App\Models\Transaction;
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
			
			foreach ($adjust_stock->items()->withoutGlobalScope("pendingItemsScope")->get() as $item){
				$item->update([
					'is_pending' => false,
				]);
				
				$item->item->serials()->whereIn('current_status',[
					'available','r_sale'
				])->update([
					'is_pending' => true
				]);
				
				if ($item->item->is_need_serial){
					foreach ($item->item->serials()->withoutGlobalScope("pendingSerialsScope")
						         ->where([["purchase_invoice_id",$adjust_stock->id],["item_id",
							         $item->item->id],["is_pending",true]])
						         ->get() as $serial
					){
//
//						echo  $serial;
						$serial->update([
							'is_pending' => false
						]);
					}
				}
			}
//			$adjust_stock->items()->withoutGlobalScope("pendingItemsScope")->update([
//				'is_pending' => false,
//
//			]);
//
			
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
