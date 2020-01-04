<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\InvoiceItems;
	
	class KitAccounting extends MathCore
	{
		
		use QtyAccounting;
		/**
		 * @param InvoiceItems $kit
		 * @param $returnQty
		 * @param $baseInc
		 *
		 * @return mixed
		 */
		public function makeReturnKit(InvoiceItems $kit,$returnQty,$baseInc)
		{
			$data['belong_to_kit'] = false;
			$data['parent_kit_id'] = 0;
			$data['discount'] = $kit->item->data->discount * $returnQty;
			$data['price'] = $kit->item->data->total;
			$data['qty'] = $returnQty;
			$data['total'] = $this->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $this->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $this->getTaxAmount($data['subtotal'],$kit->item->vts);
			$data['net'] = $this->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInc->organization_id;
			$data['creator_id'] = $baseInc->creator_id;
			$data['item_id'] = $kit->item_id;
			$data['user_id'] = $baseInc->user_id;
			$data['invoice_type'] = $baseInc->invoice_type;
			$data['is_kit'] = true;
			$createdKit = $baseInc->items()->create($data);
			$kit->updateReturnedQty($returnQty);
			return $createdKit;
		}
		
		/**
		 * @param $kit
		 */
		public function updateAmounts($kit)
		{
			$children = $kit
				->invoice
				->items()
				->where([['belong_to_kit',true],['parent_kit_id',$kit->id]])
				->get();
			
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
			
			$kit->update($result);
		}
		
	}