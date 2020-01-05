<?php
	
	
	namespace App\Accounting;
	
	
	use App\Invoice;
	use App\InvoiceItems;
	
	trait AmountsAccounting
	{
		
		/**
		 * @param Invoice $inc
		 */
		public function toUpdateIsDeletedAndIsUpdated(Invoice $inc)
		{
			$result['is_updated'] = true;
			$result['is_deleted'] = true;
			$items = $inc->items()->where('belong_to_kit',false)->get();
			foreach ($items as $item){
				if ($item['qty'] > $item['r_qty']){
					$result['is_deleted'] = false;
				}
			}
			$inc->update($result);
		}
		
		/**
		 * @param Invoice $inc
		 */
		public function toUpdateRemainingAmount(Invoice $inc)
		{
			$paid_amounts = $inc->payments()->sum('amount');
			$remaining = $inc->net - $paid_amounts;
			
			if ($remaining <= 0){
				$status = 'paid';
			}else{
				$status = 'credit';
			}
			
			$inc->update([
				'remaining' => $remaining,
				'current_status' => $status
			]);
			
		}
		
		/**
		 * to updated amounts for parent
		 * parent can be invoice or kit as invoiceItems and other
		 *
		 * @param $parent
		 */
		public function toGetAndUpdatedAmounts($parent)
		{
			$children = [];
			if ($parent instanceof Invoice){
				$children = $parent
					->items()
					->where([['belong_to_kit',false]])
					->get();
				$discount_field = 'discount_value';
				
				$result['total'] = 0;
				$result['subtotal'] = 0;
				$result['tax'] = 0;
				$result[$discount_field] = 0;
				$result['net'] = 0;
				$result['remaining'] = 0;
				$items = $children;
				
				foreach ($items as $item){
					$result['total'] = $result['total'] + $item['total'];
					$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
					$result['tax'] = $result['tax'] + $item['tax'];
					$result[$discount_field] = $result[$discount_field] + $item['discount'];
					$result['net'] = $result['net'] + $item['net'];
				}
				
				$parent->update($result);
				
				
			}else if ($parent instanceof InvoiceItems){
				$children = $parent
					->invoice
					->items()
					->where([['belong_to_kit',true],['parent_kit_id',$parent->id]])
					->get();
				$discount_field = 'discount';
				$result['total'] = 0;
				$result['subtotal'] = 0;
				$result['tax'] = 0;
				$result[$discount_field] = 0;
				$result['net'] = 0;
				$items = $children;
				foreach ($items as $item){
					$result['total'] = $result['total'] + $item['total'];
					$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
					$result['tax'] = $result['tax'] + $item['tax'];
					$result[$discount_field] = $result[$discount_field] + $item['discount'];
					$result['net'] = $result['net'] + $item['net'];
				}
				$parent->update($result);
			}
			
		}
		
	}