<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\InvoiceItems;
	use App\Item;
	use App\ItemSerials;
	
	trait InvoiceItemHelper
	{
		
		public function make_invoice_transaction($sub_invoice,$expenses)
		{
			
			
			$creator_stock = auth()->user()->manager_current_stock();
			
			if (in_array($sub_invoice->invoice_type,['purchase','beginning_inventory'])){
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
				
				
			}elseif ($sub_invoice->invoice_type == 'r_purchase'){
				
				$this->item->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $this->subtotal,
					'user_id' => $this->user_id,
					'invoice_id' => $this->invoice_id,
					'description' => 'to_item',
				]);
				
			}else if ($sub_invoice->invoice_type == 'sale'){
				
				$amount = $this->item->cost * $this->qty;
				
				
				$this->item->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $this->user_id,
					'invoice_id' => $this->invoice_id,
					'description' => 'to_item',
				]);
				
			}else if ($sub_invoice->invoice_type == 'r_sale'){

//
				
				$amount = $this->item->cost * $this->qty;
//				var_dump($this->item);
//				exit();
				
				$this->item->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $this->user_id,
					'invoice_id' => $this->invoice_id,
					'description' => 'to_item',
				]);
				
				
			}
			
		}
		
		// create expenses in item
		public function add_expenses_to_invoice_item($expenses,$widget = 1)
		{
			$result = [];
			
			$total_of_expenses = 0;
			foreach ($expenses as $expense){
				
				
				$amount = $expense['amount'] * $widget / $this->item->get_item_purchase_tax_as_value(); //
				
				$total_of_expenses = $total_of_expenses + $amount;
				
				$data = [
					'amount' => $amount,
					'invoice_id' => $this->invoice->id,
					'expense_id' => $expense['id'],
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'is_paid' => $expense['is_apended_to_net']
				];
				
				$result[] = $this->item->expenses()->create($data);
			}
			return $total_of_expenses;
		}
		
		public function init_return_kit($qty,$invoice)
		{
			
			$accounting_data = [];
			
			$main_kit_aty = $this->qty;
			$accounting_data['discount'] = $this->discount / $this->qty * $qty;
			$accounting_data['total'] = $this->total / $this->qty * $qty;
			$accounting_data['subtotal'] = $this->subtotal / $this->qty * $qty;
			$accounting_data['tax'] = $this->tax / $this->qty * $qty;
			$accounting_data['net'] = $this->net / $this->qty * $qty;
//			$accounting_data['qty'] = $qty;
			$accounting_data['organization_id'] = auth()->user()->organization_id;
			$accounting_data['creator_id'] = auth()->user()->id;
			$accounting_data['item_id'] = $this->id;
			$accounting_data['type'] = 'new';
			$accounting_data['qty'] = $qty;
			$accounting_data['cost'] = $this->item->cost;
			$accounting_data['price'] = $this->price / $this->qty * $qty;
			$accounting_data['invoice_type'] = 'r_sale';
			$accounting_data['user_id'] = $this->invoice->user_id;
			$accounting_data['is_kit'] = true;
			$kit = $invoice->items()->create(collect($accounting_data)->toArray());
			$this->update_returned_qty($qty,$this->id);
			$items = $this->invoice->items()->where([
				['belong_to_kit',true],
				['parent_kit_id',$this->item_id]
			])->get();
			
			
			foreach ($items as $item){
				$item->init_return_kit_item($kit,$qty,$invoice,$main_kit_aty,$this);
			}
			
		}
		
		public function update_returned_qty($qty,$id)
		{
			
			$new_r_qty = $this->r_qty + $qty;
			
			$item = InvoiceItems::find($id);
			$item->update([
				'r_qty' => $new_r_qty
			]);
			
			
		}
		
		/**
		 * @param $kit
		 * @param $qty
		 * @param $invoice
		 * @param $main_kit_aty
		 * @param $parent_item
		 */
		public function init_return_kit_item($kit,$qty,$invoice,$main_kit_aty,$parent_item)
		{
			
//			$qty = $this->qty / $main_kit_aty * $qty;
//			$accounting_data['discount'] = $this->discount / $this->qty * $qty;
//			$accounting_data['total'] = $this->total / $this->qty * $qty;
//			$accounting_data['subtotal'] = $this->subtotal / $this->qty * $qty;
//			$accounting_data['tax'] = $this->tax / $this->qty * $qty;
//			$accounting_data['net'] = $this->net / $this->qty * $qty;
//			$accounting_data['organization_id'] = auth()->user()->organization_id;
//			$accounting_data['creator_id'] = auth()->user()->id;
//			$accounting_data['item_id'] = $this->item_id;
//			$accounting_data['type'] = 'new';
//			$accounting_data['qty'] = $qty;
//			$accounting_data['r_qty'] = $qty;
//			$accounting_data['cost'] = $this->item->cost;
//			$accounting_data['price'] = $this->price;
//			$accounting_data['invoice_type'] = 'r_sale';
//			$accounting_data['user_id'] = $this->invoice->user_id;
//			$accounting_data['is_kit'] = false;
//			$accounting_data['belong_to_kit'] = true;
//			$accounting_data['parent_kit_id'] = $kit->id;
//			$new_item = $invoice->items()->create(collect($accounting_data)->toArray());
//
//			$this->item->update_qty_with_option('add',$qty);
//
//
//			$this->update_item_cost_value_after_new_invoice_created();
//
//			$serials = ItemSerials::where([
//				['sale_invoice_id',$parent_item->invoice->id],
//				['item_id',$parent_item->item_id],
//			])->get();
//			if ($this->item->is_need_serial)
//				$this->item->change_serials_array_status('r_sale',$serials,$invoice);
//
//
//			$this->update_returned_qty($qty,$this->id);
//
//			$new_item->make_invoice_transaction($invoice,0);
//
//
		}
		
		/**
		 *
		 */
		public function update_item_cost_value_after_new_invoice_created()
		{
			$cost = $this->item->cost;
			$current_stock = $cost * $this->item->available_qty;
			$result = [];
			$result['cost'] = $cost;
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
				'cost' => $result['cost']
			]);
//
		
		}
		
		public function init_return_item($invoice_data_invoice_item = [],$sub_invoice)
		{
			
			$target_invoice_type = $this->detect_target_invoice_type();
			$is_item_need_serial_number = $this->item->is_need_serial;
			
			$get_serials_if_need_serails = $is_item_need_serial_number ? $this->detect_returned_serials_list
			($invoice_data_invoice_item['serials'],$target_invoice_type) : [];
			
			
			$get_returned_item_qty = $is_item_need_serial_number ? count($get_serials_if_need_serails) :
				$invoice_data_invoice_item['returned_qty'];
			$has_avalilable_qty = $target_invoice_type == 'r_purchase' ?
				$this->item->check_if_has_available_qty_can_handle_purchase_return_process
				($get_returned_item_qty) :
				$this->item->check_if_has_available_qty_can_handle_sale_return_process($get_returned_item_qty);
			if ($has_avalilable_qty){
				$return_item_data = $this->get_item_return_data($get_returned_item_qty,$target_invoice_type);
				$return_item_data['qty'] = $get_returned_item_qty;
				$return_item_data['item_id'] = $invoice_data_invoice_item['item_id'];
				$return_item_data['r_qty'] = $get_returned_item_qty;
				$return_item_data['type'] = 'return';
				$return_item_data['invoice_type'] = $target_invoice_type;
				$new_item = $sub_invoice->items()->create($return_item_data);
				if ($target_invoice_type == 'r_purchase')
					$this->item->update_qty_with_option('sub',$get_returned_item_qty);
				else
					$this->item->update_qty_with_option('add',$get_returned_item_qty);
				
				$this->update_item_cost_value_after_new_invoice_created();
//
				
				if ($is_item_need_serial_number)
					$this->item->change_serials_array_status($target_invoice_type,$get_serials_if_need_serails,
						$sub_invoice);
				
				
				$this->update_returned_qty($get_returned_item_qty,$invoice_data_invoice_item['id']);
				$new_item->make_invoice_transaction($sub_invoice,0);
//
			}
			
		}
		
		/**
		 * @return string
		 */
		public function detect_target_invoice_type()
		{
			return $this->invoice_type == 'sale' ? 'r_sale' : 'r_purchase';
		}
		
		/**
		 * @param $serials
		 * @param $target_invoice_type
		 *
		 * @return array
		 */
		private function detect_returned_serials_list($serials,$target_invoice_type)
		{
			$result = [];
			
			
			foreach ($serials as $serial){
				if ($serial['current_status'] == $target_invoice_type){
					$fresh_serial = $this->item->serials()->where('id',$serial['id'])->first();
					if ($fresh_serial['current_status'] != $target_invoice_type){
						$result[] = $serial;
					}
				}
			}
			
			
			return $result;
			
		}
		
		public function get_item_return_data($qty = 0,$invoice_type)
		{
			
			
			$data['total'] = $this->price * $qty;
			$data['discount'] = $this->discount / $this->qty * $qty;
			$data['tax'] = $this->tax / $this->qty * $qty;
			
			$data['subtotal'] = $data['total'] - $data['discount'];
			$data['net'] = $data['subtotal'] + $data['tax'];
			$data['organization_id'] = $this->organization_id;
			$data['item_id'] = $this->id;
			$data['user_id'] = $this->user_id;
			$data['price'] = $this->price;
			$data['creator_id'] = auth()->user()->id;
			
			
			if ($invoice_type == 'sale')
				$data['cost'] = $this->item->cost;
			else
				$data['cost'] = $this->cost;
			
			return $data;
			
		}
		
	}