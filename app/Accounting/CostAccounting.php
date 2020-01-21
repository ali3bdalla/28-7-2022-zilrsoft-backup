<?php
	
	
	namespace App\Accounting;
	
	
	use App\Core\MathCore;
	use App\Invoice;
	use App\Item;
	
	trait CostAccounting
	{
		
		use ExpensesAccounting;
		
		/**
		 * @param Item $item
		 */
		public function toGetItemCostTransactionHistoryList(Item $item)
		{
//			$histories = $item->history()->where('invoice_type','!=','quotation')->with('invoice','user','creator')->get();
//			$cost = 0;
//			$stock_value = 0;
//			$stock_qty = 0;
//			$profit = 0;
//			$movement = [];
//			foreach ($histories as $history){
//
//				$result = [
//					'cost' => $cost,
//					'stock_value' => $stock_value,
//					'stock_qty' => $stock_qty,
//				];
//
//
//				$history['description'] = "";
//				$history['invoice_url'] = $history['urls']['invoice_url'];
//				$history['invoice_title'] = $history['urls']['invoice_title'];
//
//
////				return 1;
////				return $history;
//				if ($history['qty'] > 0){
//					$history['price'] = $history['total'] / $history['qty'];
//				}
//
//
//				if (in_array($history['invoice_type'],['purchase','beginning_inventory'])){
//					$result = $this->handlePurchaseHistory($history,$stock_value,$stock_qty);
//				}elseif ($history['invoice_type'] == 'r_sale'){
//					$profit -= $history['price'] - $history['cost'] - $history['discount'];
//					$result = $this->handleReturnSaleHistory($history,$cost,$stock_value,$stock_qty);
//				}elseif ($history['invoice_type'] == 'r_purchase'){
//					$result = $this->handleReturnPurchaseHistory($history,$cost,$stock_value,$stock_qty);
//				}elseif ($history['invoice_type'] == 'sale'){
//
//					$profit += $history['price'] - $history['cost'] - $history['discount'];
//					$result = $this->handleSaleHistory($history,$cost,$stock_value,$stock_qty);
//				}
//
//
//				$cost = $result['final_stock_cost'];
//				$stock_value = $result['final_stock_total'];
//				$stock_qty = $result['final_stock_qty'];
//				$movement['data'][] = $result;
//
//			}
//
//
//			$movement['stock_value'] = $cost * $stock_qty;
//			$movement['cost'] = $cost;
//			$movement['stock_qty'] = $stock_qty;
//			$movement['profits'] = $profit;
//
//
//			$this->update_item_cost($cost,$stock_qty);
//
//			return $movement;
		}
		
		/**
		 * @param $incItem
		 *
		 * @return mixed
		 */
		public function toUpdateCostAfterInvoiceCreated($item,$incItem)
		{
			$mathCore = new MathCore();
			$totalCost = $mathCore->getTotalAmount($item->cost,$item->available_qty);
			$result['cost'] = $item->cost;
			if (in_array($incItem->invoice->invoice_type,['purchase','beginning_inventory'])){
				$result = $this->toUpdatePurchasesCost($incItem,$totalCost,$item->available_qty);
			}else if ($incItem->invoice->invoice_type == 'sale'){
				$result = $this->toUpdateSalesCost($incItem,$item->cost,$totalCost,$item->available_qty);
			}else if ($incItem->invoice->invoice_type == 'r_sale'){
				$result = $this->toUpdateSalesReturnCost($incItem,$item->cost,$item->available_qty);
			}else if ($incItem->invoice->invoice_type == 'r_purchase'){
				$result = $this->toUpdatePurchasesReturnCost($incItem,$item->cost,$totalCost,$item->available_qty);
			}
			
			$final_cost = $result['cost'];
			$item->update([
				'cost' => $final_cost
			]);
			return $result['cost'];
		}
		
		/**
		 * @param $incItemTransaction
		 * @param $currentStockAmount
		 * @param $currentStockQty
		 *
		 * @return mixed
		 */
		public function toUpdatePurchasesCost($incItemTransaction,$currentStockAmount,$currentStockQty)
		{
			$initItemTransaction = $incItemTransaction;
			$initItemTransaction['current_move_stock_qty'] = $currentStockQty + $incItemTransaction['qty'];
			$initItemTransaction['current_move_stock_total'] = $currentStockAmount + $incItemTransaction['total'];
			if ($initItemTransaction['current_move_stock_qty'] > 0)
				$initItemTransaction['current_move_stock_cost'] = $initItemTransaction['current_move_stock_total'] / $initItemTransaction['current_move_stock_qty'];
			if ($incItemTransaction['item']['is_service'])
				$initItemTransaction['current_move_stock_cost'] = 0;
			$initItemTransaction['final_stock_total'] = $initItemTransaction['current_move_stock_total'];
			$initItemTransaction['final_stock_cost'] = $initItemTransaction['current_move_stock_cost'];
			$initItemTransaction['final_stock_qty'] = $initItemTransaction['current_move_stock_qty'];
			if ($incItemTransaction['discount'] > 0){
				$initItemTransaction = $this->toGetPurchasesDiscountCost($initItemTransaction);
			}
			$initItemTransaction = $this->toGetPurchaseExpenseCost($initItemTransaction);
			
			return $initItemTransaction;
		}
		
		/**
		 * @param $purchaseItemTransaction
		 *
		 * @return mixed
		 */
		private function toGetPurchasesDiscountCost($purchaseItemTransaction)
		{
			$after_discount_stock_total = $purchaseItemTransaction['current_move_stock_total'] - $purchaseItemTransaction['discount'];
			if ($purchaseItemTransaction['current_move_stock_qty'] > 0){
				$cost = $after_discount_stock_total / $purchaseItemTransaction['current_move_stock_qty'];
			}else{
				$cost = $purchaseItemTransaction['current_move_stock_qty'];
			}
			$purchaseItemTransaction['has_purchase_discount'] = true;
			$purchaseItemTransaction['discount_data'] = [
				'discount_stock_total' => $after_discount_stock_total,
				'discount_stock_cost' => $cost
			];
			$purchaseItemTransaction['total_cost'] = $cost * $purchaseItemTransaction['qty'];
			$purchaseItemTransaction['final_stock_total'] = $after_discount_stock_total;
			$purchaseItemTransaction['final_stock_cost'] = $cost;
			$purchaseItemTransaction['final_stock_qty'] = $purchaseItemTransaction['current_move_stock_qty'];
			return $purchaseItemTransaction;
		}
		
		/**
		 * @param $purchaseItemTransaction
		 *
		 * @return mixed
		 */
		private function toGetPurchaseExpenseCost($purchaseItemTransaction)
		{
			$invoice = Invoice::find($purchaseItemTransaction['invoice_id']);
			if (empty($invoice))
				return $purchaseItemTransaction;
			$expenses = $this->toGetPurchaseExpenses($invoice);
			if (empty($expenses))
				return $purchaseItemTransaction;
			
			
			$expenses_data = [];
			foreach ($expenses as $expense){
				$incItemTransaction['final_stock_total'] = $purchaseItemTransaction['final_stock_total'] + $expense['amount'];// -
				$expense['expense_stock_total'] = $incItemTransaction['final_stock_total'];
				if ($incItemTransaction['final_stock_qty'] > 0){
					$expense['expense_stock_cost'] = $incItemTransaction['final_stock_total'] / $incItemTransaction['final_stock_qty'];
				}else{
					$expense['expense_stock_cost'] = 0;
				}
				$expenses_data[] = $expense;
			}
			
			
			if ($purchaseItemTransaction['final_stock_qty'] > 0){
				$cost = $purchaseItemTransaction['final_stock_total'] / $purchaseItemTransaction['final_stock_qty'];
			}else{
				$cost = 0;
			}
			$purchaseItemTransaction['final_stock_cost'] = $cost;
			$purchaseItemTransaction['has_expenses'] = true;
			$purchaseItemTransaction['expenses_data'] = $expenses_data;
			$purchaseItemTransaction['total_cost'] = $cost * $purchaseItemTransaction['qty'];
			return $purchaseItemTransaction;
		}
		
		/**
		 * @param $incItemTransaction
		 * @param $cost
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
		public function toUpdateSalesCost($incItemTransaction,$cost,$stock_value,$stock_qty)
		{
			$incItemTransaction['current_move_stock_qty'] = $stock_qty - $incItemTransaction['qty'];
			$new_final_stock_total = $incItemTransaction['current_move_stock_qty'] * $cost;
			
			// $stock_value - $paid_qty_value
			$incItemTransaction['current_move_stock_total'] = $new_final_stock_total;
			
			if ($incItemTransaction['current_move_stock_qty'] > 0){
				$incItemTransaction['current_move_stock_cost'] = $incItemTransaction['current_move_stock_total'] / $incItemTransaction['current_move_stock_qty'];
			}else{
				$incItemTransaction['current_move_stock_cost'] = 0;
				
			}
			
			$incItemTransaction['total_cost'] = $cost * $incItemTransaction['qty'];
			$incItemTransaction['profits'] = $incItemTransaction['total'] - $incItemTransaction['total_cost'];
			
			
			$incItemTransaction['final_stock_total'] = $incItemTransaction['current_move_stock_total'];
			$incItemTransaction['final_stock_cost'] = $incItemTransaction['current_move_stock_cost'];
			$incItemTransaction['final_stock_qty'] = $incItemTransaction['current_move_stock_qty'];
			
			
			if ($incItemTransaction['discount'] > 0){
				$incItemTransaction = $this->toGetSalesDiscount($incItemTransaction);
				$incItemTransaction['profits'] = $incItemTransaction['profits'] - $incItemTransaction['discount'];
			}
			
			
			return $incItemTransaction;
			
			
		}
		
		/**
		 * @param $incItemTransaction
		 *
		 * @return mixed
		 */
		public function toGetSalesDiscount($incItemTransaction)
		{
			
			$incItemTransaction['has_sale_discount'] = true;
			$incItemTransaction['discount_data'] = [
				'discount_stock_total' => $incItemTransaction['current_move_stock_total'],
				'discount_stock_cost' => $incItemTransaction['current_move_stock_cost'],
				'discount_profits' => $incItemTransaction['discount'] * -1
			];
			
			return $incItemTransaction;
		}
		
		/**
		 * @param $incItemTransaction
		 * @param $cost
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
		public function toUpdateSalesReturnCost($incItemTransaction,$cost,$stock_qty)
		{
			$incItemTransaction['current_move_stock_qty'] = $stock_qty + $incItemTransaction['qty'];
			$incItemTransaction['current_move_stock_total'] = $cost * $incItemTransaction['current_move_stock_qty'];
			$incItemTransaction['current_move_stock_cost'] = $cost;
			$incItemTransaction['total_cost'] = $cost * $incItemTransaction['qty'];
			$incItemTransaction['profits'] = $incItemTransaction['total'] - $incItemTransaction['total_cost'];
			if ($incItemTransaction['discount'] > 0){
				$incItemTransaction = $this->toGetSalesReturnDiscountCost($incItemTransaction);
			}
			$incItemTransaction['profits'] = $incItemTransaction['profits'] * -1;
			$incItemTransaction['final_stock_cost'] = $incItemTransaction['current_move_stock_cost'];
			$incItemTransaction['final_stock_qty'] = $incItemTransaction['current_move_stock_qty'];
			return $incItemTransaction;
		}
		
		/**
		 * @param $incItemTransaction
		 *
		 * @return mixed
		 */
		private function toGetSalesReturnDiscountCost($incItemTransaction)
		{
			
			$incItemTransaction['has_return_sale_discount'] = true;
			$incItemTransaction['discount_data'] = [
				'discount_profits' => $incItemTransaction['discount'],
				'discount_stock_total' => $incItemTransaction['current_move_stock_total'],
				'discount_stock_cost' => $incItemTransaction['current_move_stock_cost']
			];
			
			return $incItemTransaction;
		}
		
		/**
		 * @param $incItemTransaction
		 * @param $cost
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
		public function toUpdatePurchasesReturnCost($incItemTransaction,$cost,$stock_value,$stock_qty)
		{
			
			$incItemTransaction['current_move_stock_qty'] = $stock_qty - $incItemTransaction['qty'];
			$incItemTransaction['current_move_stock_total'] = $stock_value - $incItemTransaction['total'];
			
			if ($incItemTransaction['current_move_stock_qty'] > 0){
				$incItemTransaction['current_move_stock_cost'] = $incItemTransaction['current_move_stock_total'] / $incItemTransaction['current_move_stock_qty'];
			}else{
				$incItemTransaction['current_move_stock_cost'] = $cost;
			}
			
			
			$incItemTransaction['final_stock_total'] = $incItemTransaction['current_move_stock_total'];
			$incItemTransaction['final_stock_cost'] = $incItemTransaction['current_move_stock_cost'];
			$incItemTransaction['final_stock_qty'] = $incItemTransaction['current_move_stock_qty'];
			
			$incItemTransaction['total_cost'] = $cost * $incItemTransaction['qty'];
			
			if ($incItemTransaction['discount'] > 0){
				$incItemTransaction = $this->toGetPurchasesReturnDiscountCost($incItemTransaction);
			}
			
			
			return $incItemTransaction;
		}
		
		/**
		 * @param $incItemTransaction
		 *
		 * @return mixed
		 */
		private function toGetPurchasesReturnDiscountCost($incItemTransaction)
		{
			
			$new_current_move_stock_total = $incItemTransaction['current_move_stock_total'] + $incItemTransaction['discount'];
			
			if ($incItemTransaction['current_move_stock_qty'] > 0){
				$cost = $new_current_move_stock_total / $incItemTransaction['current_move_stock_qty'];
			}else{
				$cost = 0;
			}
			$incItemTransaction['has_return_purchase_discount'] = true;
			$incItemTransaction['discount_data'] = [
				'discount_stock_total' => $new_current_move_stock_total,
				'discount_stock_cost' => $cost
			];
			
			
			$incItemTransaction['final_stock_total'] = $new_current_move_stock_total;
			$incItemTransaction['final_stock_cost'] = $cost;
			$incItemTransaction['final_stock_qty'] = $incItemTransaction['current_move_stock_qty'];
			
			
			return $incItemTransaction;
		}
		
	}