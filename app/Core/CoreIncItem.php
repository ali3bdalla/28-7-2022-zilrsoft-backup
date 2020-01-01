<?php
	
	
	namespace App\Core;
	
	
	use App\ItemSerials;
	use Dotenv\Exception\ValidationException;
	
	trait CoreIncItem
	{
		
		/**
		 * @param array $userData
		 * @param $inc
		 *
		 * @return mixed
		 */
		public function addQtyReturn($userData = [],$inc)
		{
			// this mean the old invoice item
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
			print_r($baseItem);
			$this->item->runCostUpdater($baseItem);
			$this->item->runAvailableQtyUpdater($inc,$qty);
			
			if (!$this->item->is_kit){
				if ($inc->invoice_type == 'r_sale'){
					$baseItem->make_invoice_transaction($inc->sale,0);
				}else{
					$baseItem->make_invoice_transaction($inc->purchase,0);
				}
			}
			
			if ($this->item->is_need_serial){
				ItemSerials::markReturnAs(
					collect($userData['serials'])
						->pluck('serial')
						->toArray(),$inc);
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
				if ($qty > $this->qty || $qty > $this->item->available_qty){
					
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