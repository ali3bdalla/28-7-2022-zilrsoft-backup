<?php
	
	
	namespace App\Accounting;
	
	
	trait QtyTransactionAccounting
	{
		
		public function updateInvoiceItemReturnedQty($item,$returnedQty)
		{
			$current_qty = $item->r_qty + $returnedQty;
			
			$item->update([
				'r_qty' => $current_qty
			]);
			
		}
	}