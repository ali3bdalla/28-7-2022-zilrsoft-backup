<?php
	
	namespace App\DatabaseHelpers;
	
	
	use App\PurchaseInvoice;
	use App\SaleInvoice;
	
	trait ItemHelper
	{
		
		public function init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses)
		{
			
			
			if ($sub_invoice instanceof SaleInvoice){
				if ($this->available_qty < $invoice_item['qty']){
					throw  ValidationException([
						'qty' => 'not available_qty'
					]);
				}
			}
			
			
			$qty = $this->get_item_qty($invoice_item); //  detect qty of the item should be created
			
			$accounting_data = $this->get_item_accounting_data_except_price($qty,$invoice_item);
			
			if ($sub_invoice instanceof SaleInvoice){
				if ($this->is_expense)
					$accounting_data['cost'] = $invoice_item['purchase_price'];
				else
					$accounting_data['cost'] = $this->cost;
				
			}else
				$accounting_data['cost'] = $accounting_data['total'] / $qty;
			
			
			$accounting_data['price'] = $this->get_item_price($invoice_type,$invoice_item);
			
			
			$accounting_data['invoice_type'] = $invoice_type;
			
			if (collect($invoice_item)->has('belong_to_kit')){
				
				if ($invoice_item['belong_to_kit']){
					$accounting_data['belong_to_kit'] = true;
					$accounting_data['parent_kit_id'] = $invoice_item['parent_kit_id'];
				}
			}
			
			
			$accounting_data['user_id'] = $user_id;
			
			
			$new_invoice_item = $sub_invoice->invoice->items()->create(collect($accounting_data)->toArray());
			
			$new_invoice_item->update_item_cost_value_after_new_invoice_created();
			
			
			if (!empty($expenses))
				$total_of_expenses = $new_invoice_item->add_expenses_to_invoice_item($expenses,
					$invoice_item['widget']);
			else
				$total_of_expenses = 0;
			
			
			//update serial if item need contain serials array
			if ($this->is_need_serial)
				$this->
				update_serials_list_for_the_item_after_new_invoice_created(
					$invoice_type
					,$sub_invoice,
					$invoice_item,
					$user_id
				);
			
			
			$this->update_item_qty_after_new_invoice_created($qty,$invoice_type);
			
			if ($sub_invoice instanceof PurchaseInvoice && !$invoice_item['is_expense']){
				$this->update_item_last_purchase_price($accounting_data['price']);
				$this->update_item_sales_price($invoice_item['price_with_tax']);
				
			}
			
			$new_invoice_item->make_invoice_transaction($sub_invoice,$total_of_expenses);
			
			
			return $this;
		}
		
		public function get_item_qty($source_request_item_data)
		{
			return $source_request_item_data['is_need_serial'] == true ? count($source_request_item_data['serials']) : $source_request_item_data['qty'];
		}
		
		public function get_item_accounting_data_except_price($qty,$source_request_item_data)
		{
			
			if ($this->is_kit){
				$result = $this->fetchKitData($qty,$source_request_item_data);
			}else
				$result = collect($source_request_item_data)->only(['discount','tax','net','total','subtotal','cost']);
			
			
			$result['organization_id'] = auth()->user()->organization_id;
			$result['creator_id'] = auth()->user()->id;
			$result['item_id'] = $this->id;
			$result['type'] = 'new';
			$result['qty'] = $qty;
			return $result;
			
		}
		
		public function get_item_price($invoice_type,$source_request_item_data)
		{
			if (in_array($invoice_type,['purchase','beginning_inventory'])){
				$price = $source_request_item_data['purchase_price'];
			}else{
				
				if ($source_request_item_data['is_expense']){
					$price = $source_request_item_data['price'];
				}else{
					if ($this->is_fixed_price){
						$price = $this->price;
					}else{
						$price = $source_request_item_data['price'];
					}
					
				}
				
				
				$accounting_data['invoice_type'] = 'sale';
				
			}
			
			
			return $price;
		}
		
		public function update_serials_list_for_the_item_after_new_invoice_created(
			$invoice_type,
			$created_invoice,
			$source_request_item_data,$user_id
		)
		{
			
			if ($created_invoice instanceof PurchaseInvoice){
				$created_invoice->created_new_item_serial_for_within_this_purchase_invoice(
					$source_request_item_data,
					$invoice_type,
					$user_id
				);
				
			}else{
				
				$created_invoice->
				set_item_serials_status_as_paid_for_this_sale_invoice($source_request_item_data['serials']);
				
				
			}
			
			
		}
		
		public function update_item_qty_after_new_invoice_created($qty,$invoice_type)
		{
			
			if (in_array($invoice_type,['purchase','beginning_inventory','r_sale'])){
				$this->update_qty_with_option('add',$qty);
			}else{
				
				$this->update_qty_with_option('sub',$qty);
				
				
			}
			
			
		}
		
		public function update_qty_with_option($option,$qty)
		{
			
			$qty = intval($qty);
			$old_available_qty = $this->available_qty;
			if ($option == 'add'){
				$this->update([
					'available_qty' => $old_available_qty + $qty
				]);
				return true;
			}else{
				
				$this->update([
					'available_qty' => $old_available_qty - $qty
				]);
				return true;
			}
		}
		
		public function update_item_last_purchase_price($price)
		{
			$this->update([
				'last_p_price' => $price
			]);
		}
		
		public function update_item_sales_price($price)
		{
			if (is_numeric($price)){
				$price_without_tax = $price / $this->get_item_tax_as_value();
				$this->update([
					'price' => $price_without_tax,
					'price_with_tax' => $price
				]);
			}
			
		}
		
		public function get_item_tax_as_value()
		{
			return 1 + $this->vts / 100;
		}
		
		public function init_quotation_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses)
		{
			
			$qty = $invoice_item['qty']; //  detect qty of the item should be created
			
			
			$accounting_data = $this->get_item_accounting_data_except_price($qty,$invoice_item);
			$accounting_data['type'] = 'quotation';
			$accounting_data['cost'] = 0;
			$accounting_data['price'] = $invoice_item['price'];
			$accounting_data['invoice_type'] = 'quotation';
			$accounting_data['user_id'] = $user_id;
			
			$new_invoice_item = $sub_invoice->invoice->items()->create(collect($accounting_data)->toArray());
			
			return $this;
		}
		
		public function get_item_purchase_tax_as_value()
		{
			return 1 + $this->vtp / 100;
		}
		
		public function get_item_expenses_total($expenses,$invoice_widget)
		{
			$total_expenses = 0;
			foreach ($expenses as $expens){
			
			}
			
			return $total_expenses;
		}
		
		public function get_invoice_item_expenses($invoice_id)
		{
			return $this->expenses()->where('invoice_id',$invoice_id)->with('expense')->get();
		}
		
		/*
		 * to check if item has good qty now avaiable to handle return proccess
		 *
		 * */
		public function check_if_has_available_qty_can_handle_purchase_return_process($returned_qty)
		{
			return $this->available_qty >= $returned_qty;
		}
		
		public function check_if_has_available_qty_can_handle_sale_return_process($returned_qty)
		{
			return true;
		}
		
		public function change_serials_array_status($status,$serials = [],$invoice = null)
		{
			
			if ($status == 'r_sale'){
				$data = [
					'current_status' => $status,
					'r_sale_invoice_id' => $invoice->id,
				];
				$user_id = $invoice->sale->client_id;
			}else{
				$data = [
					'current_status' => $status,
					'r_purchase_invoice_id' => $invoice->id,
				];
				$user_id = $invoice->purchase->vendor_id;
			}
			$this->serials()->whereIn('id',collect($serials)->pluck('id')->toArray())->update($data);
			foreach ($serials as $serial){
				$invoice->serial_history()->create([
					'event' => $status,
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'serial_id' => $serial['id'],
					'user_id' => $user_id
				]);
			}
			
			
		}
		
	}