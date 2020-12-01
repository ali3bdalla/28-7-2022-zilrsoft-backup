<?php
	
	namespace App\Http\Requests\Accounting\Inventory;
	
	use App\Models\Accounting\AmountsAccounting;
	use App\Models\Accounting\ExpensesAccounting;
	use App\Models\Accounting\IdentityAccounting;
	use App\Models\Accounting\ItemAccounting;
	use App\Models\Accounting\PaymentAccounting;
	use App\Models\Accounting\QtyTransactionAccounting;
	use App\Models\Accounting\TransactionAccounting;
	use App\Core\MathCore;
	use App\Models\Invoice;
	use App\Models\Item;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateAdjustStockRequest extends FormRequest
	{
		use TransactionAccounting,ExpensesAccounting,AmountsAccounting,QtyTransactionAccounting;
		
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
				'items' => 'required|array',
				'items.*.id' => ['required','integer','organization_exists:App\Models\Item,id'],
				'items.*.qty' => 'required|integer|min:1',
			];
		}
		
		public function save()
		{
// 			DB::beginTransaction();
// 			try{
				
// 				$invoice = Invoice::publish([
// 					'invoice_type' => 'stock_adjust',
// 					'parent_id' => 0,
// 					'is_deleted' => 1,
// 				]);
// 				foreach ($this->input('items') as $item){
// 					$freshItem = Item::findOrFail($item['id']);
// 					$result_items[] = $this->toCreateAdjustStockIncItemAndTransaction($freshItem,$item,
// 						$invoice->fresh());
					
// 				}
				
// 				$this->toGetAndUpdatedAmounts($invoice);


// //				$this->toCreateInvoiceTransactions($invoice,$this->input('items'),[],[]);
				
				
// 				$invoice->update([
// 					'current_status' => 'paid'
// 				]);
				
				
// 				DB::commit();
				
// 				return $invoice;
// 			}catch (Exception $exception){
				
// 				DB::rollBack();
				
// 				return response(json_encode([
// 					'message' => $exception->getMessage()
// 				]),400);
// 			}
		}
		
		/**
		 * @param Item $item
		 * @param $userData
		 * @param Invoice $inc
		 *
		 * @return
		 */
		public function toCreateAdjustStockIncItemAndTransaction(Item $item,$userData,Invoice $inc)
		{
			$mathCore = new MathCore();
			$data['belong_to_kit'] = false;
			$data['is_pending'] = true;
			$data['parent_kit_id'] = false;
			
			$data['discount'] = 0;
			$data['price'] = $item->cost;
			$data['cost'] = $item->cost;
			$data['qty'] = $userData['qty'];
			$data['total'] = $mathCore->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $mathCore->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $item->getTaxAmount($data['subtotal'],$item->vtp);
			$data['net'] = $item->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $inc->organization_id;
			$data['creator_id'] = $inc->creator_id;
			$data['item_id'] = $item->id;
			$data['user_id'] = 0;
			$data['invoice_type'] = $inc->invoice_type;
			$baseItem = $inc->items()->create($data);
			if (!$item->is_service){
				$qtyData['qty'] = $userData['qty'] - $item->qty;
				$qtyData['variation'] = $qtyData['qty'] < 0 ? "less" : "greater";
				$qtyData['qty'] = abs($qtyData['qty']);

//				$this->toUpdateItemAvailableQty($item,$userData['qty'],"set");
				$this->toCreateIncItemTransaction($baseItem->fresh(),$inc,$expenses = 0,$qtyData);
//				$this->toUpdateCostAfterInvoiceCreated($item,$baseItem);
				
				if ($item->is_need_serial){
					if (count($userData['serials']) > 0){
						foreach ($userData['serials'] as $serial){
							$new_serial = $item->serials()->create([
								'organization_id' => auth()->user()->organization_id,
								'creator_id' => auth()->user()->id,
								'purchase_invoice_id' => $inc->id,
								'item_id' => $userData['id'],
								'serial' => $serial,
								'current_status' => 'available',
								'is_pending' => true
							]);
							
							
							$inc->serial_history()->create([
								'event' => 'purchase',
								'organization_id' => auth()->user()->organization_id,
								'creator_id' => auth()->user()->id,
								'serial_id' => $new_serial->id,
								'user_id' => $inc->user_id
							]);

//
						}
					}
				}
				
				
			}
			
			
			return $baseItem;
			
			
		}
		
	}
