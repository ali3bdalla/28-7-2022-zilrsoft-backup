<?php
	
	namespace App\Processers;
	
	
	trait  ItemProcesser
	{
		use ItemMovement;
		
	}
	
	trait  ItemMovement
	{
		
		public function stockMovement($dates = [])
		{
			$histories = $this->history()->with('invoice','user','creator')->get();
			
			
			$cost = 0;
			$stock_value = 0;
			$stock_qty = 0;
			
			$profit = 0;
			$movement = [];
			foreach ($histories as $history){
				
				$result = [
					'cost' => $cost,
					'stock_value' => $stock_value,
					'stock_qty' => $stock_qty,
				];
				
				$history['description'] = $history['description'];
				$history['invoice_url'] = $history['urls']['invoice_url'];
				$history['invoice_title'] = $history['urls']['invoice_title'];
				
				
				if ($history['qty'] > 0){
					$history['price'] = $history['total'] / $history['qty'];
				}
				
				
				if (in_array($history['invoice_type'],['purchase','beginning_inventory'])){
					
					
					$result = $this->handlePurchaseHistory($history,$stock_value,$stock_qty);
				}elseif ($history['invoice_type'] == 'r_sale'){
					$profit -= $history['price'] - $history['cost'] - $history['discount'];
					$result = $this->handleReturnSaleHistory($history,$cost,$stock_value,$stock_qty);
				//	dd($result['final_stock_qty']);
				}elseif ($history['invoice_type'] == 'r_purchase'){
					$result = $this->handleReturnPurchaseHistory($history,$cost,$stock_value,$stock_qty);
//
				
				}elseif ($history['invoice_type'] == 'sale'){
					
//					if($stock_qty!=7)
//						dd($stock_qty);
					
					$profit += $history['price'] - $history['cost'] - $history['discount'];
					$result = $this->handleSaleHistory($history,$cost,$stock_value,$stock_qty);
				}

//				dd($result['final_stock_cost']);
				
				$cost = $result['final_stock_cost'];
				$stock_value = $result['final_stock_total'];
				$stock_qty = $result['final_stock_qty'];
				$movement['data'][] = $result;
				
			}
			
			
			$movement['stock_value'] = $stock_value;
			$movement['cost'] = $cost;
			$movement['stock_qty'] = $stock_qty;
			$movement['profits'] = $profit;
			
			$this->update_item_cost($cost);
			
			return $movement;
		}
		
		public function handlePurchaseHistory($history,$stock_value,$stock_qty)
		{
			$result = $history;
			
			$result['current_move_stock_qty'] = $stock_qty + $history['qty'];
			$result['current_move_stock_total'] = $stock_value + $history['total'];
			if ($result['current_move_stock_qty'] > 0)
				$result['current_move_stock_cost'] = $result['current_move_stock_total'] / $result['current_move_stock_qty'];
			
			if ($history['is_service'])
				$result['current_move_stock_cost'] = 0;
			
			
			$result['final_stock_total'] = $result['current_move_stock_total'];
			$result['final_stock_cost'] = $result['current_move_stock_cost'];
			$result['final_stock_qty'] = $result['current_move_stock_qty'];
			
			if ($history['discount'] > 0){
				$result = $this->handlePurchaseDiscountHistory($result);
			}
			
			$result = $this->handlePurchaseExpensesHistroy($result);
			
			return $result;
		}
		
		public function handlePurchaseDiscountHistory($history)
		{
			
			$after_discount_stock_total = $history['current_move_stock_total'] - $history['discount'];
			
			if ($history['current_move_stock_qty'] > 0){
				$cost = $after_discount_stock_total / $history['current_move_stock_qty'];
			}else{
				$cost = $history['current_move_stock_qty'];
			}
			
			
			$history['has_purchase_discount'] = true;
			$history['discount_data'] = [
				'discount_stock_total' => $after_discount_stock_total,
				'discount_stock_cost' => $cost
			];
			$history['total_cost'] = $cost * $history['qty'];
			
			
			$history['final_stock_total'] = $after_discount_stock_total;
			$history['final_stock_cost'] = $cost;
			$history['final_stock_qty'] = $history['current_move_stock_qty'];
			
			
			return $history;
		}
		
		public function handlePurchaseExpensesHistroy($history)
		{
			
			$expenses = $this->get_invoice_item_expenses($history['invoice_id']);
			
			
			if (empty($expenses)){
				return $history;
			}
			
			
			$expenses_data = [];
			foreach ($expenses as $expense){
				
				
				$history['final_stock_total'] = $history['final_stock_total'] + $expense['amount'];// -
				// $history['discount']
				$expense['expense_stock_total'] = $history['final_stock_total'];
				if ($history['final_stock_qty'] > 0){
					$expense['expense_stock_cost'] = $history['final_stock_total'] / $history['final_stock_qty'];
					
				}else{
					$expense['expense_stock_cost'] = 0;
				}

//				$expense['expense_stock_cost'] = 0;
				$expenses_data[] = $expense;
			}
			
			
			if ($history['final_stock_qty'] > 0){
				$cost = $history['final_stock_total'] / $history['final_stock_qty'];
			}else{
				$cost = 0;
			}
			$history['final_stock_cost'] = $cost;
			$history['has_expenses'] = true;
			$history['expenses_data'] = $expenses_data;
			$history['total_cost'] = $cost * $history['qty'];
			return $history;
		}
		
		public function handleReturnSaleHistory($history,$cost,$stock_value,$stock_qty)
		{
			$history['current_move_stock_qty'] = $stock_qty + $history['qty'];
			$history['current_move_stock_total'] = $cost * $history['current_move_stock_qty'];
			$history['current_move_stock_cost'] = $cost;
			$history['total_cost'] = $cost * $history['qty'];
			$history['profits'] = $history['total'] - $history['total_cost'];
			if ($history['discount'] > 0){
				$history = $this->handleReturnSaleDiscountHistory($history);
				// $history['total_cost'] = $cost * $history['qty'] + $history['discount'];
				//$history['profits'] = $history['profits'] - $history['discount'];
			}
			
			
			$history['profits'] = $history['profits'] * -1;
			
			
			$history['final_stock_cost'] = $history['current_move_stock_cost'];
			$history['final_stock_qty'] = $history['current_move_stock_qty'];
			
			return $history;
		}
		
		public function handleReturnSaleDiscountHistory($history)
		{

//			$new_current_move_stock_total = $history['current_move_stock_total'] + $history['discount'];
//
////			if ($history['current_move_stock_qty'] > 0){
////				$cost = $new_current_move_stock_total / $history['current_move_stock_qty'];
////			}else{
////				$cost = 0;
////			}
			$history['has_return_sale_discount'] = true;
			$history['discount_data'] = [
				'discount_profits' => $history['discount'],
				'discount_stock_total' => $history['current_move_stock_total'],
				'discount_stock_cost' => $history['current_move_stock_cost']
			];


//			$history['final_stock_total'] = $new_current_move_stock_total;
//			$history['final_stock_cost'] = $cost;
//			$history['final_stock_qty'] = $history['current_move_stock_qty'];


//	    $history['total_cost'] = $cost * $history['qty'];
			return $history;
		}
		
		public function handleReturnPurchaseHistory($history,$cost,$stock_value,$stock_qty)
		{
			
			$history['current_move_stock_qty'] = $stock_qty - $history['qty'];
			$history['current_move_stock_total'] = $stock_value - $history['total'];
			
			if ($history['current_move_stock_qty'] > 0){
				$history['current_move_stock_cost'] = $history['current_move_stock_total'] / $history['current_move_stock_qty'];
			}else{
				$history['current_move_stock_cost'] = $cost;
			}
			
			
			$history['final_stock_total'] = $history['current_move_stock_total'];
			$history['final_stock_cost'] = $history['current_move_stock_cost'];
			$history['final_stock_qty'] = $history['current_move_stock_qty'];
			
			$history['total_cost'] = $cost * $history['qty'];
			
			if ($history['discount'] > 0){
				$history = $this->handleReturnPurchaseDiscountHistory($history);
			}
			
			
			return $history;
		}
		
		public function handleReturnPurchaseDiscountHistory($history)
		{
			
			$new_current_move_stock_total = $history['current_move_stock_total'] + $history['discount'];
			
			if ($history['current_move_stock_qty'] > 0){
				$cost = $new_current_move_stock_total / $history['current_move_stock_qty'];
			}else{
				$cost = 0;
			}
			$history['has_return_purchase_discount'] = true;
			$history['discount_data'] = [
				'discount_stock_total' => $new_current_move_stock_total,
				'discount_stock_cost' => $cost
			];
			
			
			$history['final_stock_total'] = $new_current_move_stock_total;
			$history['final_stock_cost'] = $cost;
			$history['final_stock_qty'] = $history['current_move_stock_qty'];


//	    $history['total_cost'] = $cost * $history['qty'];
			return $history;
		}
		
		public function handleSaleHistory($history,$cost,$stock_value,$stock_qty)
		{
			$history['current_move_stock_qty'] = $stock_qty - $history['qty'];
			$new_final_stock_total = $history['current_move_stock_qty'] * $cost;
			
			// $stock_value - $paid_qty_value
			$history['current_move_stock_total'] = $new_final_stock_total;
			
			if ($history['current_move_stock_qty'] > 0){
				$history['current_move_stock_cost'] = $history['current_move_stock_total'] / $history['current_move_stock_qty'];
			}else{
				$history['current_move_stock_cost'] = 0;
				
			}
			
			$history['total_cost'] = $cost * $history['qty'];
			$history['profits'] = $history['total'] - $history['total_cost'];
			
			
			$history['final_stock_total'] = $history['current_move_stock_total'];
			$history['final_stock_cost'] = $history['current_move_stock_cost'];
			$history['final_stock_qty'] = $history['current_move_stock_qty'];
			
			
			if ($history['discount'] > 0){
				$history = $this->handleSaleDiscountHistory($history);
				$history['profits'] = $history['profits'] - $history['discount'];
			}
			
			
			return $history;
			
			
		}
		
		public function handleSaleDiscountHistory($history)
		{

//        $stock_value = $history['stock_value'] + $history['discount'];
			$history['has_sale_discount'] = true;
			$history['discount_data'] = [
				'discount_stock_total' => $history['current_move_stock_total'],
				'discount_stock_cost' => $history['current_move_stock_cost'],
				'discount_profits' => $history['discount'] * -1
			];
//	    $stock_value = $history['stock_value'] + $history['discount'];

//
//			$history['final_stock_total'] = $history['current_move_stock_total'];
//			$history['final_stock_cost'] = $history['current_move_stock_cost'];
//			$history['final_stock_qty'] = $history['current_move_stock_qty'];
//
			
			return $history;
		}
		
		public function update_item_cost($cost)
		{
			$this->update(
				[
					'cost' => $cost
				]
			);
		}
		
	}
