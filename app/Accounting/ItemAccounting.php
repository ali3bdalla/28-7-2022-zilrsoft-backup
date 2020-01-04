<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\Item;
	use App\ItemSerials;
	use App\KitItems;
	
	class ItemAccounting extends MathCore
	{
		
		public function toGetKitChildItemReturnAccountingData(KitItems $kitItem,$createdKit)
		{
			$kitReturnedQty = $createdKit->qty;
			$childReturnedQty = $kitItem->qty * $kitReturnedQty;
			$childReturnedDiscount = $kitItem->discount / $kitItem->qty * $childReturnedQty;
			$childReturnedTotal = $kitItem->total / $kitItem->qty * $childReturnedQty;
			$childReturnedSubTotal = $kitItem->subtotal / $kitItem->qty * $childReturnedQty;
			$childReturnedTax = $kitItem->tax / $kitItem->qty * $childReturnedQty;
			$childReturnedNet = $kitItem->net / $kitItem->qty * $childReturnedQty;
			
			$ItemReturnAccountingData = [
				'belong_to_kit' => true,
				'kit_id' => $createdKit->id,
				'returned_qty' => $childReturnedQty,
				'total' => $childReturnedTotal,
				'subtotal' => $childReturnedSubTotal,
				'tax' => $childReturnedTax,
				'net' => $childReturnedNet,
				'discount' => $childReturnedDiscount,
			];
			return $ItemReturnAccountingData;


//			$kitItem->item;
//			$item = Item::findOrFail($child->item_id);
//			if()
//			$sendData['serials'] = ItemSerials::where([
//				['sale_invoice_id',$this->invoice->id],
//				['item_id',$kitItem['item_id']],
//				['current_status',"saled"],
//			])->get();


//			$child->addQtyReturn($sendData,$baseInvoice);
		}
	}