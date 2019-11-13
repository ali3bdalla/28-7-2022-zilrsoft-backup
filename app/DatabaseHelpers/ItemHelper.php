<?php
	
	namespace App\DatabaseHelpers;
	
	
	use App\Item;
	use App\PurchaseInvoice;
	use App\SaleInvoice;
	use Illuminate\Support\Facades\DB;
	
	trait ItemHelper
	{
		
		public function init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses)
		{
			
			$qty = $this->get_item_qty($invoice_item); //  detect qty of the item should be created
			$accounting_data = $this->get_item_accounting_data_except_price($qty,$invoice_item);
			
			if ($sub_invoice instanceof SaleInvoice)
				$accounting_data['cost'] = $this->cost;
			else
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
			// create and update item data
			
			if ($this->is_kit){
				$accounting_data['is_kit'] = true;
				
			}
			
			
			$new_invoice_item = $sub_invoice->invoice->items()->create(collect($accounting_data)->toArray());
			$new_invoice_item->update_item_cost_value_after_new_invoice_created();
			
			
			if (!empty($expenses))
				$new_invoice_item->add_expenses_to_invoice_item($expenses,$invoice_item['widget']);
			
			//update serial if item need contain serials array
			if ($this->is_need_serial)
				$this->
				update_serials_list_for_the_item_after_new_invoice_created(
					$invoice_type
					,$sub_invoice,
					$invoice_item,
					$user_id
				);
			
			if ($this->is_kit){
				foreach ($invoice_item['items'] as $item){
					
					/// create new item with the , that item is belong to kit
					$fresh_item = Item::find($item['id']);
					$item['belong_to_kit'] = true;
					$item['parent_kit_id'] = $this->id;
					$item['qty'] = $item['qty'] * $invoice_item['qty'];
					$fresh_item->init_create_invoice_item($item,$invoice_type,$user_id,$sub_invoice);
					
				}
			}
			
			
			if (!$this->is_kit)
				$this->update_item_qty_after_new_invoice_created($qty,$invoice_type);
			
			
			if (!$this->is_kit){
				if ($sub_invoice instanceof PurchaseInvoice){
					$this->update_item_last_purchase_price($accounting_data['price']);
					$this->update_item_sales_price($invoice_item['price_with_tax']);
					
				}
			}
			
			
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
				
				$accounting_data['invoice_type'] = 'sale';
				if ($this->is_fixed_price){
					$price = $this->price;
				}else{
					$price = $source_request_item_data['price'];
				}
				
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
			if (!in_array($invoice_type,['purchase','beginning_inventory'])){
				$this->update_qty_with_option('sub',$qty);
			}else{
				
				$this->update_qty_with_option('add',$qty);
				
				
			}
			
			
		}
		
		public function update_qty_with_option($option,$qty)
		{
			
			if ($option == 'add'){
				$this->update([
					'available_qty' => DB::raw("available_qty + $qty ")
				]);
				return true;
			}else{
				
				$this->update([
					'available_qty' => DB::raw("available_qty - $qty ")
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
		
		public function get_invoice_item_expenses($invoice_id)
		{
			return $this->expenses()->where('invoice_id',$invoice_id)->with('expense')->get();
		}
		
	}