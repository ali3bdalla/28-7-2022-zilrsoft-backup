<?php
	
	
	namespace App\Components\Loader\Item\Transactions;
	
	
	use App\Models\InvoiceItems;
	use Carbon\Carbon;
	
	trait DBHelper
	{
		private function updateItemStockData()
		{
			$this->item->fresh()->update([
				'available_qty' => $this->item_current_stock_qty,
				'cost' => $this->item_current_cost
			]);
		}
		
		/**
		 * @return mixed
		 */
		private function sendDatabaseQuery()
		{
			$this->initQuery();
			$this->addParams();
			$this->fetchRows();
		}
		
		private function initQuery()
		{
			$this->db_query = $this->item->history();
		}
		
		private function addParams()
		{
			$this->db_query = $this->db_query->where([
				['invoice_type','!=','quotation'],
				['invoice_type','!=','pending_purchase']
			]);
			if ($this->request->has('start_at')
				&& $this->request->filled('start_at')
				&& $this->request->has('end_at')
				&& $this->request->filled('end_at')){
				$start_at = Carbon::parse($this->request->input("start_at"))->toDateString();
				$end_at = Carbon::parse($this->request->input("end_at"))->toDateString();
				$this->start_at_date = $start_at;
				$this->db_query =
					$this->
					db_query->
					whereBetween(
						'created_at',[
							$start_at,
							$end_at
						]
					);
			}
			
		}
		
		private function fetchRows()
		{
			
			$this->db_rows = $this->db_query->with('invoice','user','creator')->paginate($this->getPerPage());

//
//			if($this->start_at_date==null)
//				$this->db_rows = $this->db_query->with('invoice','user','creator')->paginate($this->getPerPage());
//			else
//				$this->db_rows = $this->db_query->with('invoice','user','creator')->paginate
//				($this->db_query->count());
			
			
			$this->result_pagination = $this->db_rows;
//			$this->db_query->chunk(10000,function ($rows){
//				foreach ($rows as $row)
//					$this->db_rows[] = $row;
//			});
		}
		
		private function getPerPage()
		{
			return
				$this->request->has('perPage') &&
				$this->request->filled("perPage") ? $this->request->input('perPage') : 20;
		}
		
		private function fetchTransactionsResults()
		{
			$results = [];
			foreach ($this->db_rows as $row)
				$results[] = $this->transactionQueries($row);
			
			return $results;
			
		}
		
		private function transactionQueries(InvoiceItems $row)
		{
			$transaction = $this->detectTargetInvoiceTypeMethod($row);
			
			if ($this->isFreshQueryWithoutFilters())
				$this->updateTransactionMissedData($row);
			
			return $transaction;
		}
		
		/**
		 * @param InvoiceItems $row
		 *
		 * @return mixed|null
		 */
		private function detectTargetInvoiceTypeMethod(InvoiceItems $row)
		{
			$transaction = null;
			if (in_array($row['invoice_type'],['purchase','beginning_inventory']))
				$this->purchaseTransaction($row);
			if ($row->invoice_type == 'sale')
				$this->salesTransaction($row);
			if ($row->invoice_type == 'r_sale')
				$this->returnSalesTransactions($row);
			if ($row->invoice_type == 'r_purchase')
				$this->returnPurchaseTransaction($row);
			if ($row->invoice_type == 'stock_adjust')
				$this->adjustStockTransaction($row);
			$transaction = $this->convertRowIntoTransaction($row);
			$this->resetTransactionVaraiables();
			return $transaction;
		}
		
		/**
		 * @param $row
		 *
		 * @return mixed
		 */
		private function convertRowIntoTransaction($row)
		{
			$row->discount_data = $this->transactions_discount_details;
			$row->expenses_data = $this->transactions_expenses_details;
			$row->current_stock_amount = floatval(money_format("%i",$this->transaction_stock_amount));
			$row->current_stock_qty = $this->item_current_stock_qty;
			$row->current_stock_item_cost = floatval(money_format("%i",$this->item_current_cost));
			$row->current_profits = floatval(money_format("%i",$this->transaction_profits));
			
			$row->invoice_url = $row['urls']['invoice_url'];
			$row->invoice_title = $row['urls']['invoice_title'];
			
			return $row;
		}
		
		private function resetTransactionVaraiables()
		{
			$this->transaction_stock_cost = 0;
			$this->transaction_stock_amount = 0;
			$this->transaction_profits = 0;
			$this->transactions_discount_details = [
				'purchase_discount' => false,
				'sales_discount' => false,
				'return_sales_discount' => false,
				'return_purchase_discount' => false,
			];
			$this->transactions_expenses_details = [];
		}
		
		private function updateTransactionMissedData(InvoiceItems $row)
		{
			$row->fresh()->update([
				'cost' => $this->item_current_cost,
				'item_available_qty' => $this->item_current_stock_qty
			]);
		}
	}