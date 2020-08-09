<?php
	
	
	namespace App\Components\Loader\Item\Transactions;
	
	
	use App\InvoiceItems;
	
	trait TransactionsHelper
	{
		
		protected function adjustStockTransaction(InvoiceItems $row)
		{
			
			$this->item_current_stock_qty = $row->qty;
			$this->item_current_stock_amount = $this->item_current_cost * $this->item_current_stock_qty;
			if ($row->is_service)
				$this->item_current_cost = 0;
			
			$this->transaction_description = 'جرد المخزون';
			return $row;
		}
		
		protected function returnPurchaseTransaction(InvoiceItems $row)
		{
			
			$this->item_current_stock_qty -= $row->qty;
			$this->item_current_stock_amount -= $row->total;
			$this->transaction_stock_amount = $row->item_current_stock_amount;
			
			if ($this->item_current_stock_qty > 0){
				$this->item_current_cost = $this->item_current_stock_amount / $this->item_current_stock_qty;
			}else{
				$this->item_current_cost = 0;
			}
			$this->transaction_stock_amount = $row->item_current_stock_amount;
			$this->transaction_stock_cost = $row->item_current_cost;
			
			if ($row->discount > 0){
				$this->transactions_discount_details = $this->returnPurchaseDiscountTransaction($row);
			}
			
			
			return $row;
		}
		
		protected function returnPurchaseDiscountTransaction(InvoiceItems $row)
		{
			
			$this->item_current_stock_amount += $row->discount;
			
			if ($this->item_current_stock_qty > 0){
				$this->item_current_cost = $this->item_current_stock_amount / $this->item_current_stock_qty;
			}
			
			return [
				'return_sales_discount' => false,
				'sales_discount' => false,
				'purchase_discount' => false,
				'return_purchase_discount' => true,
				'discount_stock_total' => $this->item_current_stock_amount,
				'discount_stock_cost' => $this->item_current_cost
			];
			
		}
		
		protected function salesTransaction(InvoiceItems $row)
		{
			$this->item_current_stock_qty -= $row->qty;
//			dd( $this->item_current_stock_qty);
			$this->item_current_stock_amount = $this->item_current_stock_qty * $this->item_current_cost;
			$this->transaction_stock_amount = $this->item_current_stock_amount;

			$this->transaction_stock_cost = $this->item_current_cost;
			$this->transaction_profits = ($row->total - ($this->item_current_cost * $row->qty));
			$this->item_sales_total_profits += $this->transaction_profits;
			if ($row->discount > 0){
				$this->transactions_discount_details = [
					'return_sales_discount' => false,
					'sales_discount' => true,
					'purchase_discount' => false,
					'return_purchase_discount' => false,
					'discount_stock_total' => $this->item_current_stock_amount,
					'discount_stock_cost' => $this->item_current_cost,
				];
			}

//            dd($this->transaction_stock_amount);
//			$this->transaction_stock_amount = $row->item_current_stock_amount;
			$this->transaction_stock_cost = $row->item_current_cost;


			
			return $row;
			
			
		}
		
		protected function returnSalesTransactions(InvoiceItems $row)
		{
			$this->item_current_stock_qty += $row->qty;
			$this->item_current_stock_amount = $this->item_current_stock_qty * $this->item_current_cost;
			$this->transaction_profits = ($row->total - ($this->item_current_cost * $row->qty)) * -1;
			$this->item_sales_total_profits += $this->transaction_profits;
			if ($row->discount > 0){
				$this->transactions_discount_details = [
					'return_sales_discount' => true,
					'sales_discount' => false,
					'purchase_discount' => false,
					'return_purchase_discount' => false,
					'discount_stock_total' => $this->item_current_stock_amount,
					'discount_stock_cost' => $this->item_current_cost
				];
			}
			$this->transaction_stock_amount = $row->item_current_stock_amount;
			$this->transaction_stock_cost = $row->item_current_cost;
			return $row;
		}
		
		protected function purchaseTransaction(InvoiceItems $row)
		{
			$this->item_current_stock_qty += $row->qty;
			$this->item_current_stock_amount += $row->total;
			
			if (!$row->item->is_service){
				if ($this->item_current_stock_qty > 0)
					$this->item_current_cost = $this->item_current_stock_amount / $this->item_current_stock_qty;
			}else{
				$this->item_current_cost = 0;
			}
			
			$this->transaction_stock_amount = $this->item_current_stock_amount;
			$this->transaction_stock_cost = $this->item_current_cost;
			
			if ($row->discount > 0){
				$this->transactions_discount_details = $this->purchaseDiscountTransaction($row);
			}
			$this->transactions_expenses_details = $this->purchaseExpensesTransaction($row);
			return $row;
		}
		
		/**
		 * @param InvoiceItems $row
		 *
		 * @return array
		 */
		protected function purchaseDiscountTransaction(InvoiceItems $row)
		{
			
			$this->item_current_stock_amount = $this->item_current_stock_amount - $row->discount;
			
			if ($this->item_current_stock_qty > 0){
				$this->item_current_cost = $this->item_current_stock_amount / $this->item_current_stock_qty;
			}else{
				$this->item_current_cost = 0;
			}
			return [
				'return_sales_discount' => false,
				'sales_discount' => false,
				'purchase_discount' => true,
				'return_purchase_discount' => false,
				'discount_stock_total' => $this->item_current_stock_amount,
				'discount_stock_cost' => $this->item_current_cost
			];
			
		}
		
		/**
		 * @param $history
		 *
		 * @return mixed
		 */
		protected function purchaseExpensesTransaction(InvoiceItems $row)
		{
			
			$expenses_data = null;
			$expenses_db_list = $row->item->expenses()->where('invoice_id',$row->invoice_id)->with('expense')
				->get();
			if (!empty($expenses_db_list)){
				foreach ($expenses_db_list as $expense){
					$this->item_current_stock_amount += $expense->amount;
					$result['expense_stock_total'] = $this->item_current_stock_amount;
					if ($this->item_current_stock_qty > 0){
						$this->item_current_cost = $this->item_current_stock_amount / $this->item_current_stock_qty;
						$expense['stock_amount'] = $this->item_current_stock_amount;
						$expense['stock_cost'] = $this->item_current_cost;
						$expenses_data[] = $expense;
					}
					
					
				}
				
			}
			return $expenses_data;
		}
		
	}