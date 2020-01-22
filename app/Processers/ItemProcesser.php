<?php
	
	namespace App\Processers;
	
	
	use Illuminate\Database\Eloquent\Builder;
	
	trait  ItemProcesser
	{
		use AccountingStock,ItemMovement;
		
	}
	
	trait  ItemMovement
	{
		
		public function stockMovement($dates = [])
		{
			$histories = $this->history()->where('invoice_type','!=','quotation')->with('invoice','user','creator')->get();
			
//			return $histories;
			
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
				
				
				$history['description'] = "";
				$history['invoice_url'] = $history['urls']['invoice_url'];
				$history['invoice_title'] = $history['urls']['invoice_title'];
				
				
//				return 1;
//				return $history;
				if ($history['qty'] > 0){
					$history['price'] = $history['total'] / $history['qty'];
				}
				
				
				if (in_array($history['invoice_type'],['purchase','beginning_inventory'])){
					$result = $this->handlePurchaseHistory($history,$stock_value,$stock_qty);
				}elseif ($history['invoice_type'] == 'r_sale'){
					$profit -= $history['price'] - $history['cost'] - $history['discount'];
					$result = $this->handleReturnSaleHistory($history,$cost,$stock_value,$stock_qty);
					
				}elseif ($history['invoice_type'] == 'r_purchase'){
					$result = $this->handleReturnPurchaseHistory($history,$cost,$stock_value,$stock_qty);
				}elseif ($history['invoice_type'] == 'sale'){

					$profit += $history['price'] - $history['cost'] - $history['discount'];
					$result = $this->handleSaleHistory($history,$cost,$stock_value,$stock_qty);
				}

				
				$cost = $result['final_stock_cost'];
				$stock_value = $result['final_stock_total'];
				$stock_qty = $result['final_stock_qty'];
				$movement['data'][] = $result;
				
			}
			
			
			$movement['stock_value'] = $cost * $stock_qty;
			$movement['cost'] = $cost;
			$movement['stock_qty'] = $stock_qty;
			$movement['profits'] = $profit;
			
			
			$this->update_item_cost($cost,$stock_qty);
			
			return $movement;
		}
		
		/**
		 * @param $history
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
		public function handlePurchaseHistory($history,$stock_value,$stock_qty)
		{
			$result = $history;
			
			$result['current_move_stock_qty'] = $stock_qty + $history['qty'];
			$result['current_move_stock_total'] = $stock_value + $history['total'];
			
			if ($result['current_move_stock_qty'] > 0)
				$result['current_move_stock_cost'] = $result['current_move_stock_total'] / $result['current_move_stock_qty'];
			
			if ($history['is_service'])
				$result['current_move_stock_cost'] = 0;
			
			
			
			$result['final_stock_total'] = $result['current_move_stock_total'] ;
			$result['final_stock_cost'] = $result['current_move_stock_cost'];
			$result['final_stock_qty'] = $result['current_move_stock_qty'];
			
			if ($history['discount'] > 0){
				$result = $this->handlePurchaseDiscountHistory($result);
			}
			
			$result = $this->handlePurchaseExpensesHistroy($result);
			
			return $result;
		}
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
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
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
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
		
		/**
		 * @param $history
		 * @param $cost
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
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
			$history['final_stock_total'] = $history['final_stock_cost'] * $history['current_move_stock_qty'];
			$history['final_stock_qty'] = $history['current_move_stock_qty'];
			
			return $history;
		}
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
		public function handleReturnSaleDiscountHistory($history)
		{

			$history['has_return_sale_discount'] = true;
			$history['discount_data'] = [
				'discount_profits' => $history['discount'],
				'discount_stock_total' => $history['current_move_stock_total'],
				'discount_stock_cost' => $history['current_move_stock_cost']
			];

			return $history;
		}
		
		/**
		 * @param $history
		 * @param $cost
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
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
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
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


			return $history;
		}
		
		/**
		 * @param $history
		 * @param $cost
		 * @param $stock_value
		 * @param $stock_qty
		 *
		 * @return mixed
		 */
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
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
		public function handleSaleDiscountHistory($history)
		{

			$history['has_sale_discount'] = true;
			$history['discount_data'] = [
				'discount_stock_total' => $history['current_move_stock_total'],
				'discount_stock_cost' => $history['current_move_stock_cost'],
				'discount_profits' => $history['discount'] * -1
			];

			return $history;
		}
		
		/**
		 * @param $cost
		 */
		public function update_item_cost($cost,$stock_qty)
		{
			$this->update(
				[
					'cost' => $cost,
					'available_qty' => $stock_qty,
				]
			);
		}
		
	}
	
	trait  AccountingStock
	{
		
		public static function get_client_history($histories)
		{
			
			$total_balance = 0;
			
			$total_debit = 0;
			$total_credit = 0;
			
			$movement = [];
			foreach ($histories as $history){
				
				$history['total_balance'] = 0;
				$history['debit'] = 0;
				$history['credit'] = 0;
//
				$history['expenses_data'] = [];
				$history['discount_data'] = null;
				$history['invoice_url'] = $history['urls']['invoice_url'];
				$history['invoice_title'] = $history['urls']['invoice_title'];
				
				
				if (in_array($history['invoice_type'],['purchase','beginning_inventory'])){
					$history['debit'] = $history['net'];
					$history['total_balance'] = $history['net'] + $total_balance;
					
					$total_debit = $total_debit + $history['net'];
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => $history['discount'],
							'debit' => 0,
							'total_balance' => $history['total_balance'] - $history['discount']
						];
						
						$total_credit = $total_credit + $history['discount'];
					}
					
					$expenses = $history->item->get_invoice_item_expenses($history['invoice_id']);
					
					$total_balance = $history['total_balance'] - $history['discount'];
					
					$all_expense = [];
					foreach ($expenses as $expens){
						$expens['debit'] = $expens['amount'];
						$expens['credit'] = 0;
						$expens['total_balance'] = $total_balance + $expens['amount'];
						$total_balance = $expens['total_balance'];
						$all_expense[] = $expens;
						$total_debit = $total_debit + $expens['amount'];
						
					}
					
					$history['expenses_data'] = $all_expense;
					
				}elseif ($history['invoice_type'] == 'r_sale'){
					$history['debit'] = $history['net'];
					$history['total_balance'] = $history['net'] + $total_balance;
					
					$total_debit = $total_debit + $history['net'];
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => $history['discount'],
							'debit' => 0,
							'total_balance' => $history['total_balance'] - $history['discount']
						];
						
						$total_credit = $total_credit + $history['discount'];
						
					}
					
					
					$total_balance = $history['total_balance'] - $history['discount'];
					
					
				}elseif ($history['invoice_type'] == 'r_purchase'){
					
					
					$history['credit'] = $history['net'];
					
					$history['total_balance'] = $total_balance - $history['net'];
					
					$total_credit = $total_credit + $history['net'];
					
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => 0,
							'debit' => $history['discount'],
							'total_balance' => $history['total_balance'] + $history['discount']
						];
						$total_debit = $total_credit + $history['discount'];
					}
					
					
					$total_balance = $history['total_balance'] + $history['discount'];
					
					
				}elseif ($history['invoice_type'] == 'sale'){
					
					$history['credit'] = $history['net'];
					$history['total_balance'] = $total_balance - $history['credit'];
					
					$total_credit = $total_credit + $history['credit'];
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'debit' => $history['discount'],
							'credit' => 0,
							'total_balance' => $history['total_balance'] + $history['discount']
						];
						$total_debit = $total_debit + $history['discount'];
					}
					
					
					$total_balance = $history['total_balance'] + $history['discount'];
					
				}
				
				
				$movement['data'][] = $history;
				
			}
			
			$movement['total_debit'] = $total_debit;
			$movement['total_credit'] = $total_credit;
			
			return $movement;
		}
		
		public function get_accounting_stock($charts_ids = [])
		{
			$query = $this->history()->with('invoice.chart','user','creator')
				->whereHas('invoice',function (Builder $query) use ($charts_ids){
					$query->whereIn('chart_id',$charts_ids);
				});
			
			
			$histories = $query->get();
			$total_balance = 0;
			
			$total_debit = 0;
			$total_credit = 0;
			
			$movement = [];
			foreach ($histories as $history){
				
				$history['total_balance'] = 0;
				$history['debit'] = 0;
				$history['credit'] = 0;
//
				$history['expenses_data'] = [];
				$history['discount_data'] = null;
				$history['invoice_url'] = $history['urls']['invoice_url'];
				$history['invoice_title'] = $history['urls']['invoice_title'];
				
				
				if (in_array($history['invoice_type'],['purchase','beginning_inventory'])){
					$history['debit'] = $history['total'];
					$history['total_balance'] = $history['total'] + $total_balance;
					
					$total_debit = $total_debit + $history['total'];
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => $history['discount'],
							'debit' => 0,
							'total_balance' => $history['total_balance'] - $history['discount']
						];
						
						$total_credit = $total_credit + $history['discount'];
					}
					
					$expenses = $this->get_invoice_item_expenses($history['invoice_id']);
					
					$total_balance = $history['total_balance'] - $history['discount'];
					
					$all_expense = [];
					foreach ($expenses as $expens){
						$expens['debit'] = $expens['amount'];
						$expens['credit'] = 0;
						$expens['total_balance'] = $total_balance + $expens['amount'];
						$total_balance = $expens['total_balance'];
						$all_expense[] = $expens;
						$total_debit = $total_debit + $expens['amount'];
						
					}
					
					$history['expenses_data'] = $all_expense;
					
				}elseif ($history['invoice_type'] == 'r_sale'){
					$history['debit'] = $history['qty'] * $history['cost'];
					$history['total_balance'] = $history['qty'] * $history['cost'] + $total_balance;
					
					$total_debit = $total_debit + $history['qty'] * $history['cost'];
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => $history['discount'],
							'debit' => 0,
							'total_balance' => $history['total_balance'] - $history['discount']
						];
						
						$total_credit = $total_credit + $history['discount'];
						
					}
					
					
					$total_balance = $history['total_balance'] - $history['discount'];
					
					
				}elseif ($history['invoice_type'] == 'r_purchase'){
					
					
					$history['credit'] = $history['total'];
					
					$history['total_balance'] = $total_balance - $history['total'];
					
					$total_credit = $total_credit + $history['total'];
					
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'credit' => 0,
							'debit' => $history['discount'],
							'total_balance' => $history['total_balance'] + $history['discount']
						];
						$total_debit = $total_credit + $history['discount'];
					}
					
					
					$total_balance = $history['total_balance'] + $history['discount'];
					
					
				}elseif ($history['invoice_type'] == 'sale'){
					
					$history['credit'] = $history['qty'] * $history['cost'];
					$history['total_balance'] = $total_balance - $history['credit'];
					
					$total_credit = $total_credit + $history['credit'];
					
					if ($history['discount'] > 0){
						$history['discount_data'] = [
							'debit' => $history['discount'],
							'credit' => 0,
							'total_balance' => $history['total_balance'] + $history['discount']
						];
						$total_debit = $total_debit + $history['discount'];
					}
					
					
					$total_balance = $history['total_balance'] + $history['discount'];
					
				}
				
				
				$movement['data'][] = $history;
				
			}
			
			$movement['total_debit'] = $total_debit;
			$movement['total_credit'] = $total_credit;
			
			return $movement;
		}
		
	}
