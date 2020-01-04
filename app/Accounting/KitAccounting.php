<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\InvoiceItems;
	
	class KitAccounting extends MathCore
	{
		
		use QtyTransactionAccounting,AmountsAccounting;
		
		/**
		 * @param InvoiceItems $kit
		 * @param $returnQty
		 * @param $baseInc
		 *  to Create Kit as Invoice Item And Updated The Base Invoice Item Kit Returned Qty
		 *  to return createdKit as Invoice Items Object
		 *
		 * @return mixed
		 */
		public function makeReturnKit(InvoiceItems $kit,$returnQty,$baseInc):InvoiceItems
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
			$this->updateInvoiceItemReturnedQty($createdKit,$returnQty);
			return $createdKit;
		}
		
		/**
		 * @param $kit
		 */
		public function updateKitAmounts($kit)
		{
			$this->toGetAndUpdatedAmounts($kit);
		}
		
	}