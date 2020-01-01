<?php
	
	
	namespace App\Core;
	
	
	trait CoreItem
	{
		
		/**
		 * @param $incItem
		 *
		 * @return mixed
		 */
		public function runCostUpdater($incItem)
		{
			$mathCore = new MathCore();
			$totalCost = $mathCore->getTotalAmount($this->cost,$this->available_qty);
			$result['cost'] = $this->cost;
			if (in_array($incItem->invoice->invoice_type,['purchase','beginning_inventory'])){
				$result = $this->handlePurchaseHistory($incItem,$totalCost,$this->available_qty);
			}else if ($incItem->invoice->invoice_type == 'sale'){
				$result = $this->handleSaleHistory($incItem,$this->cost,$totalCost,$this->available_qty);
			}else if ($incItem->invoice->invoice_type == 'r_sale'){
				$result = $this->handleReturnSaleHistory($incItem,$totalCost,$this->cost,$this->available_qty);
			}else if ($incItem->invoice->invoice_type == 'r_purchase'){
				$result = $this->handleReturnPurchaseHistory($incItem,$this->cost,$totalCost,$this->available_qty);
			}
			
			$final_cost = $result['cost'];
			$this->update([
				'cost' => $final_cost
			]);
			return $result['cost'];
		}
		
		/**
		 * @param $inc_type
		 * @param $qty
		 *
		 * @return mixed
		 */
		public function runAvailableQtyUpdater($inc,$qty)
		{
			if (in_array($inc->invoice_type,['purchase','beginning_inventory','r_sale'])){
				$current_qty = $this->available_qty + $qty;
			}else{
				$current_qty = $this->available_qty - $qty;
			}
			
			$this->update([
				'available_qty' => $current_qty
			]);
			
			return $current_qty;
		}
		
	}