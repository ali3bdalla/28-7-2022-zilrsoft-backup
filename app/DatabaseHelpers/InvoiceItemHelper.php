<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\PurchaseInvoice;
	
	trait InvoiceItemHelper
	{
		
		public function make_invoice_transaction($sub_invoice,$expenses)
		{
			
			$creator_stock = auth()->user()->manager_current_stock();
			
			if ($sub_invoice instanceof PurchaseInvoice){
				$this->item->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $this->subtotal + $expenses,
					'user_id' => $this->user_id,
					'invoice_id' => $this->invoice_id,
					'description' => 'to_item',
				]);
			}
			
		}
		
		
		
		public function update_item_cost_value_after_new_invoice_created()
		{
			$cost = $this->item->cost;
			$current_stock = $cost * $this->item->available_qty;
			$result = [];
			$result['final_stock_cost'] = $cost;
			if (in_array($this->invoice->invoice_type,['purchase','beginning_inventory'])){
				$result = $this->item->handlePurchaseHistory($this,$current_stock,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'sale'){
				$result = $this->item->handleSaleHistory($this,$cost,$current_stock,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'r_sale'){
				$result = $this->item->handleReturnSaleHistory($this,$current_stock,$cost,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'r_purchase'){
				$result = $this->item->handleReturnPurchaseHistory($this,$cost,$current_stock,$this->item->available_qty);
			}
			
			
			$this->item->update([
				'cost' => $result['final_stock_cost']
			]);
			
			
		}
		
		public function add_expenses_to_invoice_item($expenses,$widget = 1)
		{
			$result = [];
			
			$total_of_expenses = 0;
			foreach ($expenses as $expense){
				
				
				// 21 / 1.05 ;
				$amount = $expense['amount'] * $widget / $this->item->get_item_purchase_tax_as_value(); //
				
				$total_of_expenses =$total_of_expenses +  $amount;
				
				$data = [
					'amount' => $amount,
					'invoice_id' => $this->invoice->id,
					'expense_id' => $expense['id'],
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'is_paid' => $expense['is_apended_to_net']
				];
//
				$result[] = $this->item->expenses()->create($data);
			}
			return $total_of_expenses;
		}
		
	}