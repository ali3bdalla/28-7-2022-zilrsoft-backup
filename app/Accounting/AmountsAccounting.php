<?php
	
	
	namespace App\Accounting;
	
	
	use App\Invoice;
	use App\InvoiceItems;
	
	trait AmountsAccounting
	{
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
			}else if ($parent instanceof InvoiceItems){
				$children = $parent
					->invoice
					->items()
					->where([['belong_to_kit',true],['parent_kit_id',$parent->id]])
					->get();
			}
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount'] = 0;
			$result['net'] = 0;
			$items = $children;
			foreach ($items as $item){
				$result['total'] = $result['total'] + $item['total'];
				$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
				$result['tax'] = $result['tax'] + $item['tax'];
				$result['discount'] = $result['discount'] + $item['discount'];
				$result['net'] = $result['net'] + $item['net'];
			}
			$parent->update($result);
		}
		
	}