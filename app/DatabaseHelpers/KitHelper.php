<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\Item;
	use App\KitItems;
	
	use Dotenv\Exception\ValidationException;
	
	trait KitHelper
	{
		
		public function init_create_invoice_kit($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses)
		{
			$qty = $invoice_item['qty'];
			$accounting_data = $this->get_item_accounting_data_except_price($qty,$invoice_item);
			
			$accounting_data['discount'] = $this->data->discount * $qty;
			$accounting_data['total'] = $this->data->total * $qty;
			$accounting_data['subtotal'] = $this->data->subtotal * $qty;
			$accounting_data['tax'] = $this->data->tax * $qty;
			$accounting_data['net'] = $this->data->net * $qty;
			$accounting_data['organization_id'] = auth()->user()->organization_id;
			$accounting_data['creator_id'] = auth()->user()->id;
			$accounting_data['item_id'] = $this->id;
			$accounting_data['type'] = 'new';
			$accounting_data['qty'] = $qty;
			
			$accounting_data['cost'] = $this->cost;
			$accounting_data['price'] = $this->get_item_price($invoice_type,$invoice_item);
			$accounting_data['invoice_type'] = $invoice_type;
			$accounting_data['user_id'] = $user_id;
			$accounting_data['is_kit'] = true;
			$kit = $sub_invoice->invoice->items()->create(collect($accounting_data)->toArray());
			
			foreach ($invoice_item['items'] as $item){
				$fresh_item_data = Item::find($item['id']);
				$fetched_data = $this->fetch_item_inside_kit_data($item,$qty,$fresh_item_data);
				$fresh_item_data->init_create_invoice_item($fetched_data,$invoice_type,$user_id,
					$sub_invoice,
					$expenses);
			}
			
			
			$this->update_kit_totals_data($sub_invoice->invoice,$kit);
		}
		
		public function fetch_item_inside_kit_data($item_ui_request_data,$kit_qty,$fresh_item_data)
		{
			$item_ui_request_data['belong_to_kit'] = true;
			$item_ui_request_data['parent_kit_id'] = $this->id;
			
			$item_kit_data = KitItems::where([[
				'kit_id',$this->id
			],[
				'item_id',$fresh_item_data->id
			]])->first();
			$qty = $item_kit_data->qty * $kit_qty;
			$this->validate_has_available_qty_to_sale($qty,$fresh_item_data);
			
			$item_ui_request_data['qty'] = $qty;
			$item_ui_request_data['total'] = $item_kit_data->total * $kit_qty;
			$item_ui_request_data['price'] = $item_kit_data->price;
			$item_ui_request_data['subtotal'] = $item_kit_data->subtotal * $kit_qty;
			$item_ui_request_data['discount'] = $item_kit_data->discount * $kit_qty;
			$item_ui_request_data['net'] = $item_kit_data->net * $kit_qty;
			$item_ui_request_data['tax'] = $item_kit_data->tax * $kit_qty;
			
			
			return $item_ui_request_data;
		}
		
		public function validate_has_available_qty_to_sale($qty,$item)
		{
			if ($item->available_qty < $qty)
				throw new ValidationException([
					'item has no available qty to handle this sale invoice'
				]);
		}
		
		public function update_kit_totals_data($invoice,$kit)
		{
			$items = $invoice->items()->where([
				[
					"belong_to_kit",true
				],
				[
					"parent_kit_id",$this->id
				]
			])->get();
			
			
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount'] = 0;
			$result['net'] = 0;
			
			
			foreach ($items as $item){
				$result['total'] = $result['total'] + $item['total'];
				$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
//				$result['tax'] = $result['tax'] + $item['tax'];
				$result['discount'] = $result['discount'] + $item['discount'];
				$result['net'] = $result['net'] + $item['net'];
				$tax = $item['subtotal'] * $item->item->vts / 100;
				$item->update([
					'tax' => $tax
				]);
				$result['tax'] = $result['tax'] + $tax;
			}


//			echo json_encode($result);
//			exit();
			
			$kit->update($result);
			
		}
		
	}