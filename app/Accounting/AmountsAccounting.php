<?php
	
	
	namespace App\Accounting;
	
	
	use App\Invoice;
	use App\InvoiceItems;
	use App\Item;
	
	trait AmountsAccounting
	{
		
		/**
		 * @param $expenses
		 *
		 * @return int
		 */
		public function toGetAmountOfNonEmbdedExpense($expenses)
		{
			$total_amount = 0;
			foreach (collect($expenses) as $expense){
				if (!$expense["is_apended_to_net"]){
					$total_amount += $expense["amount"];
				}
			}
			
			return $total_amount;
		}
		
		/**
		 * @param InvoiceItems $item
		 * @param $returnedQty
		 *
		 * @return mixed
		 */
		public function toGetAmountsForReturnedQty(InvoiceItems $item,$returnedQty)
		{
			$result['price'] = $item->getOriginal('price');
			$result['discount'] = $item->getOriginal('discount') * $returnedQty / $item->qty;
			$result['total'] = $item->getOriginal('total') * $returnedQty / $item->qty;
			$result['subtotal'] = $item->getOriginal('subtotal') * $returnedQty / $item->qty;
			$result['net'] = $item->getOriginal('net') * $returnedQty / $item->qty;
			$result['tax'] = $item->getOriginal('tax') * $returnedQty / $item->qty;
			return $result;
		}
		
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
		 * @param $parent
		 * @param int $expense_amount
		 */
		public function toGetAndUpdatedAmounts($parent,$expense_amount = 0)
		{
			
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
					$result['total'] = $result['total'] + $item->getOriginal('total');
					$result['subtotal'] = $result['subtotal'] + $item->getOriginal('subtotal');
					$result['tax'] = $result['tax'] + $item->getOriginal('tax');
					$result[$discount_field] = $result[$discount_field] + $item->getOriginal('discount');
					$result['net'] = $result['net'] + $item->getOriginal('net');
				}
				$result['net'] = $result['net'] + $expense_amount;
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

//
				
				$parent->update($result);
			}
			
		}
		
	}