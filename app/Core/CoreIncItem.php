<?php
	
	
	namespace App\Core;
	
	
	use App\Accounting\CostAccounting;
	use App\Accounting\ItemAccounting;
	use App\Accounting\KitAccounting;
	use App\Accounting\QtyTransactionAccounting;
	use App\Accounting\SerialTransactionAccounting;
	use App\Accounting\TransactionAccounting;
	
	trait CoreIncItem
	{
		use QtyTransactionAccounting,SerialTransactionAccounting,CostAccounting,TransactionAccounting;
		
		/**
		 * @param $userData
		 * @param $baseInc
		 */
		public function preformKitReturn($userData,$baseInc)
		{
			$kitAccounting = new KitAccounting();
			$createdKit = $kitAccounting->makeReturnKit($this,$userData['returned_qty'],$baseInc);
			$itemAccounting = new ItemAccounting();
			foreach ($this->item->items as $kitItem){
				$child = $this->invoice->items()->where([
					['belong_to_kit',true],
					['parent_kit_id',$this->id],
					['item_id',$kitItem->item_id]
				])->first();
				
				$result = $itemAccounting->toGetKitChildItemReturnAccountingData($kitItem,$createdKit,$userData);
				
				foreach ($result as $key => $value){
					$child[$key] = $value;
				}
				$child->preformItemReturn($child,$baseInc,$createdKit);
				
			}
			
			$kitAccounting->updateKitAmounts($createdKit);
		}
		
		/**
		 * @param array $userData
		 * @param $inc
		 *
		 * @return mixed
		 */
		public function preformItemReturn($userData = [],$inc,$createdKit = null)
		{
			
			$itemAccounting = new ItemAccounting();
			$qty = $userData['returned_qty'];
			
			$this->toValidateItemHasEnoughQtyToMakeReturn($this->fresh(),$qty,$inc->invoice_type);
			
			if ($this->is_need_serial)
				$this->toValidateSerialArrayCurrentStatus($userData,$qty,$inc->invoice_type,$this->fresh());
			$data['belong_to_kit'] = $createdKit == null ? $this->belong_to_kit : true;
			$data['parent_kit_id'] = $createdKit == null ? $this->parent_kit_id : $createdKit->id;
			$data['discount'] = $this->discount;
			$data['price'] = $this->price;
			$data['qty'] = $qty;
			$data['r_qty'] = $qty;
			$vat = $inc->invoice_type == 'r_sale' ? $this->item->vts : $this->item->vtp;
			$mathCore = new MathCore();
			if (!$this->belong_to_kit){
				$accountingAmounts = $mathCore->accountingAmount($qty,$data['price'],$data['discount'],$vat);
				foreach ($accountingAmounts as $key => $value){
					$data[$key] = $value;
				}
			}else{
				$data['total'] = $userData['total'];
				$data['subtotal'] = $userData['subtotal'];
				$data['net'] = $userData['net'];
				$data['tax'] = $userData['tax'];
			}
			
			$data['organization_id'] = $inc->organization_id;
			$data['creator_id'] = $inc->creator_id;
			$data['item_id'] = $this->item->id;
			$data['user_id'] = $inc->user_id;
			$data['invoice_type'] = $inc->invoice_type;
			$data['cost'] = $this->cost;
			$baseItem = $inc->items()->create($data);
			if (!$this->item->is_kit && !$this->item->is_service){
				$freshItem = $this->item->fresh();
				$this->toUpdateCostAfterInvoiceCreated($freshItem,$baseItem);
				$itemAccounting->toUpdateAvailableQtyAsIncEvent($freshItem,$inc,$qty);
				$this->toCreateIncItemTransaction($baseItem,$inc,0);
				if ($this->item->is_need_serial){
					$this->toUpdateSerialArrayAsGivenType(collect($userData['serials'])->pluck('serial')->toArray(),$inc);
				}
			}
			$this->toUpdateInvoiceItemReturnedQty($this->fresh(),$qty);
			return $baseItem;
		}
		
	}