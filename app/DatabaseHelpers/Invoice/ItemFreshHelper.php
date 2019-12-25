<?php
	
	
	namespace App\DatabaseHelpers\Invoice;
	
	
	use App\Http\Requests\Invoice\PurchaseCreationRequest;
	use App\Item;
	use App\ItemSerials;
	use App\Math\Math;
	use Dotenv\Exception\ValidationException;
	
	trait ItemFreshHelper
	{
		
		use Math;
		
		/**
		 * @param $baseInvoice
		 * @param $request_data
		 */
		public function addKitToBaseInvoice($baseInvoice,$request_data)
		{
			$children = $this->items;
			$qty = $request_data['qty'];
			
			$data['belong_to_kit'] = false;
			$data['parent_kit_id'] = 0;
			$data['discount'] = $this->data->discount * $qty;
			$data['price'] = $this->data->total;
			$data['qty'] = $qty;
			$data['total'] = $this->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $this->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $this->getTaxAmount($data['subtotal'],$this->vts);
			$data['net'] = $this->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInvoice->organization_id;
			$data['creator_id'] = $baseInvoice->creator_id;
			$data['item_id'] = $this->id;
			$data['user_id'] = $baseInvoice->user_id;
			$data['invoice_type'] = $baseInvoice->invoice_type;
			$data['is_kit'] = true;
			$baseItem = $baseInvoice->items()->create($data);
			foreach ($children as $child){
				$item = Item::findOrFail($child->item_id);
				if ($item->is_need_serial){
					
					$data = collect($request_data['items'])->where('id',$item->id)->first();
					if (!empty($data)){
						$sendData['serials'] = $data['serials'];
					}else{
						$sendData['serials'] = [];
					}
				}else{
					$sendData['serials'] = [];
				}
				$sendData['qty'] = $child['qty'] * $qty;
				$sendData['discount'] = $child['discount'] * $qty;
				$sendData['price'] = $child['price'];
				$sendData['belong_to_kit'] = true;
				$sendData['kit_id'] = $baseItem->id;
				$item->addToBaseInvoice($baseInvoice,$sendData);
			}
			
			$baseItem->updateKitAccountingDataDependOnItChildrenInformation();
		}
		
		/**
		 * @param $baseInvoice
		 * @param $data
		 */
		public function addToBaseInvoice($baseInvoice,$request_data)
		{
//
			
			if ($this->is_need_serial)
				$this->validateItemNeedSerials($request_data);
			
			$data['belong_to_kit'] = isset($request_data['belong_to_kit']) && $request_data['belong_to_kit'] ? true : false;
			$data['parent_kit_id'] = $data['belong_to_kit'] ? $request_data['kit_id'] : 0;
			$data['discount'] = $request_data['discount'];
			if (in_array($baseInvoice->invoice_type,['sale','r_sale'])){
				$data['price'] = $this->is_fixed_price ? $this->price : $request_data['price'];
			}else{
				$data['price'] = $request_data['purchase_price'];
			}
			
			$data['qty'] = $request_data['qty'];
			$data['total'] = $this->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $this->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $this->getTaxAmount($data['subtotal'],$this->vts);
			$data['net'] = $this->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInvoice->organization_id;
			$data['creator_id'] = $baseInvoice->creator_id;
			$data['item_id'] = $this->id;
			$data['user_id'] = $baseInvoice->user_id;
			$data['invoice_type'] = $baseInvoice->invoice_type;
			if ($this->is_expense)
				$data['cost'] = $request_data['purchase_price'];
			else
				$data['cost'] = $this->cost;
			
			
			$baseItem = $baseInvoice->items()->create($data);
			$baseItem->update_item_cost_value_after_new_invoice_created();
			$this->update_item_qty_after_new_invoice_created($data['qty'],$baseInvoice->invoice_type);
			if (in_array($baseInvoice->invoice_type,['sale','r_sale'])){
				$baseItem->make_invoice_transaction($baseInvoice->sale,0);
			}
			
			
//			if ($this->is_need_serial){
//				if (in_array($baseInvoice->invoice_type,['sale','r_sale']))
//					$baseInvoice->sale->
//					set_item_serials_status_as_paid_for_this_sale_invoice($request_data['serials']);
//				else
//					$baseInvoice->purchase->
//					set_item_serials_status_as_paid_for_this_sale_invoice($request_data['serials']);
//			}
			
			
			return $baseItem;
		}
		
		/**
		 * @param $request_data
		 */
		public function validateItemNeedSerials($request_data,$type = "sale")
		{
			$data = collect($request_data);
			
			
			if (!$data->has('serials') || empty($data['serials']))
				throw new ValidationException('item in package need to pass serials array');
			
			if (!empty($data['serials'])){
				foreach (collect($data["serials"])->pluck("serial")->toArray() as $serial){
					$db_serial = $this->serials()->where('serial',$serial)->first();
					
					
					if (empty($db_serial))
						throw new ValidationException('serial is not validate');
					else{
						if (in_array($type,['purchase','beginning_inventory'])){
							throw new ValidationException('serial is already exists');
						}
						if ($type == "sale"){
							if (in_array($db_serial['current_status'],["r_purchase","saled"])){
								throw new ValidationException('serial is already paid');
							}
							
						}else if ($type == 'r_sale'){
							if (in_array($db_serial['current_status'],["r_sale","purchase",'available'])){
								throw new ValidationException('serial is already returned');
							}
						}
						
					}
					
				}
			}
//			ex
			
			
		}
		
		/**
		 * @param $request_item
		 *
		 * @return mixed
		 */
		public function addPurchaseToExpense($request_item)
		{
			
			$purchase = new PurchaseCreationRequest();
			$item['qty'] = 1;
			$item['total'] = $request_item['purchase_price'];
			$item['subtotal'] = $request_item['total'];
			
			$item['tax'] = $this->getTotalAmount($item['total'],$this->vtp);
			$item['net'] = $this->getNetAmount($item['subtotal'],$item['tax']);
			$item['cost'] = $request_item['purchase_price'];
			
			$data['total'] = $item['total'];
			$data['subtotal'] = $item['subtotal'];
			$data['tax'] = $item['tax'];
			$data['net'] = $item['net'];
			$data['discount_percent'] = 0;
			$data['discount_value'] = 0;
			
			$gateway = auth()->user()->manager_gateway('cash');
			$gateway->amount = $item['net'];
			$gateway->is_paid = true;
			$invoice = $purchase->create_invoice($data,auth()->user());
			$sub_invoice = $purchase->create_subinvoice($invoice,auth()->user(),auth()->user()->id,$request_item['expense_vendor_id'],'0000');
			$invoice->add_items_to_invoice([$item],$sub_invoice,[],'purchase',$request_item['expense_vendor_id']);
			$invoice_status = $invoice->handle_invoice_transactions([$gateway],$request_item['expense_vendor_id'],
				$item['net'],[$item],[]);
			$invoice->update_invoice_creation_status($invoice_status);
			$invoice->update([
				"parent_invoice_id" => $this->id
			]);
			return $invoice;
		}
		
		/**
		 * @param $baseInvoice
		 * @param $request_data
		 */
		public function addReturnedKitToBaseInvoice($baseInvoice,$request_data,$parent_item = null)
		{
			$children = $this->items;
			$qty = $request_data['returned_qty'];
			
			$data['belong_to_kit'] = false;
			$data['parent_kit_id'] = 0;
			$data['discount'] = $this->data->discount * $qty;
			$data['price'] = $this->data->total;
			$data['qty'] = $qty;
			$data['total'] = $this->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $this->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $this->getTaxAmount($data['subtotal'],$this->vts);
			$data['net'] = $this->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInvoice->organization_id;
			$data['creator_id'] = $baseInvoice->creator_id;
			$data['item_id'] = $this->id;
			$data['user_id'] = $baseInvoice->user_id;
			$data['invoice_type'] = $baseInvoice->invoice_type;
			$data['is_kit'] = true;
			$baseItem = $baseInvoice->items()->create($data);
			foreach ($children as $child){
				$item = Item::findOrFail($child->item_id);
				$child_parent_item = $parent_item->invoice->items()->where([
					['belong_to_kit',true],
					['parent_kit_id',$parent_item->id],
					['item_id',$item->id],
				])->first();
				
				$sendData['serials'] = $serials = ItemSerials::where([
					['sale_invoice_id',$parent_item->invoice->id],
					['item_id',$item->id],
					['current_status',"saled"],
				])->get();
				
				
				$sendData['returned_qty'] = $child['qty'] * $qty;
				$sendData['discount'] = $child['discount'] / $child['qty'] * $sendData['returned_qty'];
				$sendData['price'] = $child['price'];
				$sendData['belong_to_kit'] = true;
				$sendData['kit_id'] = $baseItem->id;
				$item->addReturnedItemToBaseInvoice($baseInvoice,$sendData,$child_parent_item);
			}
			
			$baseItem->updateKitAccountingDataDependOnItChildrenInformation();
		}
		
		/**
		 * @param $baseInvoice
		 * @param $data
		 */
		public function addReturnedItemToBaseInvoice($baseInvoice,$request_data,$parent_item)
		{
//
			
			if ($this->is_need_serial)
				$this->validateItemNeedSerials($request_data,"r_sale");
			
			$data['belong_to_kit'] = isset($request_data['belong_to_kit']) && $request_data['belong_to_kit'] ? true : false;
			$data['parent_kit_id'] = $data['belong_to_kit'] ? $request_data['kit_id'] : 0;
			$data['discount'] = $request_data['discount'];
			$data['price'] = $parent_item['price'];
			$data['qty'] = $request_data['returned_qty'];
			$data['r_qty'] = $request_data['returned_qty'];
			$data['total'] = $this->getTotalAmount($data['price'],$data['qty']);
			$data['subtotal'] = $this->getSubTotalAmount($data['total'],$data['discount']);
			$data['tax'] = $this->getTaxAmount($data['subtotal'],$this->vts);
			$data['net'] = $this->getNetAmount($data['subtotal'],$data['tax']);
			$data['organization_id'] = $baseInvoice->organization_id;
			$data['creator_id'] = $baseInvoice->creator_id;
			$data['item_id'] = $this->id;
			$data['user_id'] = $baseInvoice->user_id;
			$data['invoice_type'] = $baseInvoice->invoice_type;
			$data['cost'] = $this->cost;


//			exit();
			$baseItem = $baseInvoice->items()->create($data);
			$baseItem->update_item_cost_value_after_new_invoice_created();
			$this->update_item_qty_after_new_invoice_created($data['qty'],$baseInvoice->invoice_type);
			$baseItem->make_invoice_transaction($baseInvoice->sale,0);
			
			
			if ($this->is_need_serial)
				$baseInvoice->sale->set_item_serials_status_as_paid_for_this_sale_invoice(collect($request_data['serials'])->pluck('serial')->toArray(),'r_sale');
			
			$parent_item->update_returned_qty($data['qty'],$parent_item->id);
			
			return $baseItem;
		}
		
	}
	