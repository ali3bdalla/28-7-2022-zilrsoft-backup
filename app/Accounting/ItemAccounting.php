<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\ItemSerials;
	use App\KitItems;
	
	
	class ItemAccounting extends MathCore
	{
		
		use QtyTransactionAccounting;
		
		/**
		 * @param $item
		 * @param $inc
		 * @param $returnQty
		 */
		public function toUpdateAvailableQtyAsIncEvent($item,$inc,$returnedQty)
		{
			$mode = in_array($inc->invoice_type,['sale','r_purchase']) ? 'sub' : 'plus';
			$this->toUpdateItemAvailableQty($item,$returnedQty,$mode);
		}
		
		/**
		 * @param $invoiceItem
		 * @param $returnQty
		 */
		public function toUpdatedItemReturnedQty($invoiceItem,$returnQty)
		{
			$this->toUpdateInvoiceItemReturnedQty($invoiceItem,$returnQty);
		}
		
		/**
		 * @param KitItems $kitItem
		 * @param $createdKit
		 *
		 * @return array
		 */
		public function toGetKitChildItemReturnAccountingData(KitItems $kitItem,$createdKit,$saleKit)
		{
			$kitReturnedQty = $createdKit->qty;
			
			$childReturnedQty = $kitItem->qty * $kitReturnedQty;
			$childReturnedDiscount = $kitItem->discount / $kitItem->qty * $childReturnedQty;
			$childReturnedTotal = $kitItem->total / $kitItem->qty * $childReturnedQty;
			$childReturnedSubTotal = $kitItem->subtotal / $kitItem->qty * $childReturnedQty;
			$childReturnedTax = $kitItem->tax / $kitItem->qty * $childReturnedQty;
			$childReturnedNet = $kitItem->net / $kitItem->qty * $childReturnedQty;
			
			$childReturnedSerials = [];
			if ($kitItem->item->is_need_serial){
				$childReturnedSerials = ItemSerials::where([
					['sale_invoice_id',$saleKit['invoice_id']],
					['item_id',$kitItem['item_id']],
					['current_status',"saled"],
				])
					->take($childReturnedQty)
					->get();
			}
			
			
			$ItemReturnAccountingData = [
				'returned_qty' => $childReturnedQty,
				'qty' => $childReturnedQty,
				'total' => $childReturnedTotal,
				'subtotal' => $childReturnedSubTotal,
				'tax' => $childReturnedTax,
				'net' => $childReturnedNet,
				'discount' => $childReturnedDiscount,
				'serials' => $childReturnedSerials
			];
			return $ItemReturnAccountingData;


//			$kitItem->item;
//			$item = Item::findOrFail($child->item_id);
//			if()
//


//			$child->addQtyReturn($sendData,$baseInvoice);
		}
	}