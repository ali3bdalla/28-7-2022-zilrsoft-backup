<?php
	
	
	namespace App\Core;
	
	
	use App\Item;
	use App\ItemSerials;
	use Dotenv\Exception\ValidationException;
	
	trait CoreIncItem
	{
		
		/**
		 * @param $baseInvoice
		 * @param $request_data
		 */
		public function addKitReturn($userData,$dbKit,$baseInvoice)
		{
			$children = $this->invoice->items()->where([
				['belong_to_kit',true],
				['parent_kit_id',$this->id ]
			])->get();
			$qty = $userData['returned_qty'];
			
			$mathCore = new MathCore();
			
			$data['belong_to_kit'] = false;
			$data['parent_kit_id'] = 0;
			$data['discount'] = $this->item->data->discount * $qty;
			$data['price'] = $this->item->data->total;
			$data['qty'] = $qty;
			$data['total'] = $mathCore->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $mathCore->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $mathCore->getTaxAmount($data['subtotal'],$this->vts);
			$data['net'] = $mathCore->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInvoice->organization_id;
			$data['creator_id'] = $baseInvoice->creator_id;
			$data['item_id'] = $this->item_id;
			$data['user_id'] = $baseInvoice->user_id;
			$data['invoice_type'] = $baseInvoice->invoice_type;
			$data['is_kit'] = true;
			$baseItem = $baseInvoice->items()->create($data);
			foreach ($children as $child){
				
//				$item = Item::findOrFail($child->item_id);
//				$sendData['serials'] = ItemSerials::where([
//					['sale_invoice_id',$this->invoice->id],
//					['item_id',$child['item_id']],
//					['current_status',"saled"],
//				])->get();
//
//
//				$sendData['returned_qty'] = $child['qty'] * $qty;
//				$sendData['discount'] = $child['discount'] / $child['qty'] * $sendData['returned_qty'];
//				$sendData['price'] = $child['price'];
//				$sendData['belong_to_kit'] = true;
//				$sendData['kit_id'] = $baseItem->id;
//				$child->addQtyReturn($sendData,$baseInvoice);
			}
			
			$baseItem->updateKitAccountingDataDependOnItChildrenInformation();
		}
		
		/**
		 * @param array $userData
		 * @param $inc
		 *
		 * @return mixed
		 */
		public function addQtyReturn($userData = [],$inc)
		{
			$qty = $userData['returned_qty'];
			$this->checkReturnQty($qty,$inc->invoice_type);
			if ($this->is_need_serial)
				$this->checkReturnSerialList($userData,$qty,$inc->invoice_type);
			$data['belong_to_kit'] = $this->belong_to_kit;
			$data['parent_kit_id'] = $this->parent_kit_id;
			$data['discount'] = $this->discount;
			$data['price'] = $this->price;
			$data['qty'] = $qty;
			$data['r_qty'] = $qty;
			$vat = $inc->invoice_type == 'r_sale' ? $this->item->vts : $this->item->vtp;
			$mathCore = new MathCore();
			$accountingAmounts = $mathCore->accountingAmount($qty,$data['price'],$data['discount'],$vat);
			foreach ($accountingAmounts as $key => $value){
				$data[$key] = $value;
			}
			$data['organization_id'] = $inc->organization_id;
			$data['creator_id'] = $inc->creator_id;
			$data['item_id'] = $this->item->id;
			$data['user_id'] = $inc->user_id;
			$data['invoice_type'] = $inc->invoice_type;
			$data['cost'] = $this->cost;
			$baseItem = $inc->items()->create($data);
			
			if (!$this->item->is_kit && !$this->item->is_service){
				$this->item->runCostUpdater($baseItem);
				$this->item->runAvailableQtyUpdater($inc,$qty);
				
				if ($inc->invoice_type == 'r_sale'){
					$baseItem->make_invoice_transaction($inc->sale,0);
				}else{
					$baseItem->make_invoice_transaction($inc->purchase,0);
				}
				
				if ($this->item->is_need_serial){
					ItemSerials::markReturnAs(
						collect($userData['serials'])
							->pluck('serial')
							->toArray(),$inc);
				}
				
			}
			
			$this->updateReturnedQty($qty);
			
			return $baseItem;
		}
		
		/**
		 * @param $qty
		 * validate qty
		 */
		public function checkReturnQty($qty,$type = 'r_sale')
		{
			if ($type == 'r_sale'){
				if ($qty > $this->qty){
					
					throw new ValidationException(
						'item.'.$this->id.'.qty'
					);
				}
			}else{
				if ($qty > $this->qty){
					throw new ValidationException(
						'item.'.$this->id.'.qty'
					);
				}
			}
			
			
		}
		
		/**
		 * @param $list
		 * @param $qty
		 */
		public function checkReturnSerialList($list,$qty,$type)
		{
			
			$data = collect($list);
			if (!$data->has('serials') || empty($data['serials']))
				throw new ValidationException('item in package need to pass serials array');
			$userSideSerialsList = collect($data["serials"]);
			foreach ($userSideSerialsList->pluck("serial")->toArray() as $serial){
				// query to get serial form database
				$query = $this->item()->serials([
					['serial',$serial],
					['sale_invoice_id',$this->invoice_id],
				])->where()->first();
				
				if (empty($query))
					throw new ValidationException('serial is not validate');
				else{
					
					if ($type == 'r_sale'){
						if ($query['current_status'] != 'sale'){
							throw new ValidationException('serial is already returned');
						}
					}else{
						if (!in_array($query['current_status'],['available','r_sale'])){
							throw new ValidationException('serial is already returned');
						}
						
					}
					
				}
				
			}
			if (count($userSideSerialsList) !== $qty)
				throw new ValidationException('item serials not equal to returned qty');
			
			
		}
		
		/**
		 * @param $qty
		 */
		public function updateReturnedQty($qty)
		{
			$current_qty = $this->r_qty + $qty;
			
			$this->update([
				'r_qty' => $current_qty
			]);
		}
	}