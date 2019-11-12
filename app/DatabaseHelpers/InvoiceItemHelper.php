<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\ItemExpenses;
	
	trait InvoiceItemHelper
	{
		
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
			
			foreach ($expenses as $expense){
				$amount = $expense['amount'] * $widget;

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
			return $result;
		}
		
		
		
	}