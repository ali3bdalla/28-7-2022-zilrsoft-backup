<?php
	
	
	namespace App\Accounting;
	
	
	use Dotenv\Exception\ValidationException;
	
	trait QtyTransactionAccounting
	{
		
		/**
		 * @param $qty
		 * @param string $type
		 */
		public function toValidateItemHasEnoughQtyToMakeReturn($incItem,$qty,$type = 'r_sale')
		{
			if ($type == 'r_sale'){
				if ($qty > $incItem->qty){
					throw new ValidationException(
						'item.'.$incItem->id.'.qty'
					);
				}
			}else{
				if(!$incItem->item->is_expense)
				{
					if ($qty > $incItem->qty || $qty > $incItem->item->available_qty){
						throw new ValidationException(
							'item.'.$incItem->id.'.qty'
						);
					}
				}
				
			}
		}
		
		/**
		 * @param $item
		 * @param $returnedQty
		 */
		public function toUpdateInvoiceItemReturnedQty($item,$returnedQty)
		{
			$current_qty = $item->r_qty + $returnedQty;
			
			$item->update([
				'r_qty' => $current_qty
			]);
			
		}
		
		/**
		 * @param $item
		 * @param $qty
		 * @param string $mode
		 */
		public function toUpdateItemAvailableQty($item,$qty,$mode = "plus")
		{
			$availableQty = $mode == 'plus' ? $item->available_qty + $qty : $item->available_qty - $qty;
			
			$item->update([
				'available_qty' => $availableQty
			]);
		}
	}