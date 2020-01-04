<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\Item;
	use App\ItemSerials;
	use App\KitItems;
	
	class ItemAccounting extends MathCore
	{
		
		public function toGetKitChildItemReturnAccountingData(KitItems $kitItem,$kitReturnedQty)
		{
			
			$childReturnedQty = $kitItem->qty * $kitReturnedQty;
			$childReturnedDiscount = $kitItem->discount / $kitItem->qty * $childReturnedQty;
			$childReturnedTotal = $kitItem->total / $kitItem->qty * $childReturnedQty;
			$childReturnedSubTotal = $kitItem->subtotal / $kitItem->qty * $childReturnedQty;
			$childReturnedTax = $kitItem->tax / $kitItem->qty * $childReturnedQty;
			$childReturnedNet = $kitItem->net / $kitItem->qty * $childReturnedQty;

//			$sendData['discount'] = $child['discount'] / $sendData['returned_qty'];
//			$sendData['total'] = $child['total'] / $sendData['returned_qty'];
//			$sendData['subtotal'] = $child['subtotal'] / $sendData['returned_qty'];
//			$sendData['tax'] = $child['tax'] / $sendData['returned_qty'];
//			$sendData['net'] = $child['net'] / $sendData['returned_qty'];
//			$sendData['price'] = $child['price'];
//			$sendData['belong_to_kit'] = true;
//			$sendData['kit_id'] = $baseItem->id;
			
			$ItemReturnAccountingData = [
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