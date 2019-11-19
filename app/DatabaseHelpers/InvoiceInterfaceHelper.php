<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use AliAbdalla\Tafqeet\Core\Tafqeet;
	use App\Account;
	use App\Invoice;
	use App\InvoiceItems;
	use App\Item;
	use App\PurchaseInvoice;
	use App\SaleInvoice;
	use Exception;
	
	trait InvoiceInterfaceHelper
	{
		
		public static function updateInvoiceStatusAsPaid($ids = [],$payment)
		{
			$invoices = Invoice::find($ids);
			if (!empty($invoices)){
				foreach ($invoices as $invoice){
					$invoice->update([
						'current_status' => 'paid',
						'remaining' => 0,
					]);
					
					$invoice->payments()->create(
						[
							'organization_id' => auth()->user()->organization_id,
							'creator_id' => auth()->user()->id,
							'payment_id' => $payment->id,
							'amount' => $invoice->remaining,
							'is_paid' => 1
						]
					);
				}
			}
			
		}
		
		
		
		
		public function createChildrenItems($items,$user_id,$sub_invoice,$invoice_type,$expenses = [])
		{
			$result = [];
			if (!empty($this->items)){
				foreach ($items as $invoice_item){
					$fresh_item = Item::find($invoice_item['id']);
					$fresh_item->init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses);
					$result[] = $fresh_item;
				}
				return $result;
			}
		}
		
		public function add_items_to_beginning_inventory($items,$user_id,$sub_invoice,$invoice_type,$expenses = [])
		{
			$result = [];
			if (!empty($this->items)){
				foreach ($items as $invoice_item){
					$fresh_item = Item::find($invoice_item['id']);
					$fresh_item->init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses);
					$result[] = $fresh_item;
				}
				return $result;
			}
		}
		
		
		public function add_items_to_invoice($items = [],$sub_invoice,$expenses,$invoice_type,$user_id)
		{
			
			
			$result = [];
			if (!empty($this->items)){
				foreach ($items as $invoice_item){
					$fresh_item = Item::find($invoice_item['id']);
					$fresh_item->init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses);
					$result[] = $fresh_item;
				}
				return $result;
			}
			
		}
		
		public function add_expenses_to_invoice($expenses = [])
		{
			
			
			if (!empty($expenses)){
				foreach ($expenses as $expense){
					if ($expense['is_open'] == true){
						$data['creator_id'] = auth()->user()->id;
						$data['organization_id'] = auth()->user()->id;
						$data['expense_id'] = $expense['id'];
						$data['amount'] = $expense['amount'];
						$data['is_paid'] = $expense['is_apended_to_net'] == true ? true : false;
						$this->expenses()->create($data);
					}
					
				}
			}
		}
		
		public function createPayments($user_id,$methods,$invoice,$type = 'receipt')
		{
			$total = 0;
			
			foreach ($methods as $method){
				if ($method['amount'] > 0){
					$data['creator_id'] = auth()->user()->id;
					$data['user_id'] = $user_id;
					$data['gateway_id'] = $method['id'];
					$data['amount'] = $method['amount'];
					$data['amount_ar_words'] = Tafqeet::arablic($method['amount']);
					$data['amount_en_words'] = Tafqeet::arablic($method['amount']);
					$data['payment_type'] = $type;
					$data['creator_id'] = auth()->user()->id;
					$payment = auth()->user()->organization->payments()->create($data);
					
					$invoice->payments()->create([
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'amount' => $method['amount'],
						'payment_id' => $payment->id,
						'is_paid' => 1
					]);
					
					$total = $total + $method['amount'];
					
				}
			}
			
			
			return $total;
		}
		
		public function createChildInvoice($child_invoice_data,$invoice_type)
		{
			$child_invoice_data['department_id'] = $this->department_id;
			$child_invoice_data['branch_id'] = $this->branch_id;
			$child_invoice_data['organization_id'] = $this->organization_id;
			$child_invoice_data['creator_id'] = auth()->user()->id;
			$child_invoice_data['current_status'] = $this->current_status;
			$child_invoice_data['issued_status'] = $this->issued_status;
			$child_invoice_data['invoice_type'] = $invoice_type;
			return $this->child()->create($child_invoice_data);
		}
		
		public function createReturnItems($items,$sub_invoice)
		{
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount_value'] = 0;
			$result['net'] = 0;
			
			if (!empty($items)){
				foreach ($items as $key => $invoice_item){
					$item = Item::find($invoice_item['item_id']);
					if ($invoice_item['returned_qty'] >= 1){
						// returned_qty more than one
						
						$fresh_invoice_item = InvoiceItems::find($invoice_item['id']);
						$return_invoice_type = $sub_invoice instanceof SaleInvoice ? 'r_sale' : 'r_purchase';
						$item_new_returned_serial_list = [];
						if ($item->is_need_serial){
							$item_new_returned_serial_list = $item->getListOfReturnedSerial($invoice_item['serials'],$return_invoice_type);
							$qty = count($item_new_returned_serial_list);
						}else{
							$qty = $invoice_item['returned_qty'];
						}
						
						
						if (!$item->checkIfItHasEnoughQtyForReturn($qty,$fresh_invoice_item,$sub_invoice))
							throw  new Exception($invoice_item['id'].' item has qty to returned');
						
						
						if ($qty >= 1){
							
							$inc_item_data = $item->getDataForReturn($qty,$fresh_invoice_item);
							$inc_item_data['qty'] = $qty;
							$inc_item_data['r_qty'] = $qty;
							$inc_item_data['type'] = 'return';
							if ($sub_invoice instanceof SaleInvoice){
								$inc_item_data['invoice_type'] = 'r_sale';
							}else{
								$inc_item_data['invoice_type'] = 'r_purchase';
								
							}
							$new_invoice_item = $this->items()->create($inc_item_data);
							$fresh_invoice_item->addToReturnedQty($qty);
							if ($sub_invoice instanceof PurchaseInvoice){
								$item->updateItemAvailableQty('sub',$qty);
							}else{
								$item->updateItemAvailableQty('add',$qty);
							}
							
							
							$new_invoice_item->update_item_cost_value_after_new_invoice_created();
							
							
							if ($item->is_need_serial)
								$item->setSerialAs($return_invoice_type,$item_new_returned_serial_list,$this);
							
							
							$result['total'] += $inc_item_data['total'];
							$result['subtotal'] += $inc_item_data['subtotal'];
							$result['tax'] += $inc_item_data['tax'];
							$result['discount_value'] += $inc_item_data['discount'];
							$result['net'] += $inc_item_data['net'];
							
						}
						
						
						// end of returned qty is more than one
					}
				}
			}
			
			
			return $result;
		}
		
		public function update_invoice_creation_status($status)
		{
			$this->update([
				'issued_status' => $status,
				'current_status' => $status,
			]);
		}
		
		public function handle_invoice_transactions($methods = [],$user_id,$net,$items,$expenses)
		{
			
			
			$creator_stock = auth()->user()->manager_current_stock();
			$paid_amount = 0;
			
			foreach ($methods as $method){
				
				if ($method['amount'] > 0){
					
					$gateway = Account::find($method['id']);
					
					$gateway->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'debitable_id' => $creator_stock->id,
						'debitable_type' => get_class($creator_stock),
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'description' => 'to_stock',
					]);
					
					
					$this->handle_invoice_payments($method);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			
			$this->create_tax_transaction($creator_stock,$user_id,$items,$expenses);
			
			
			if ($paid_amount < $net){
				
				
				$this->user()->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => floatval($net) - floatval($paid_amount),
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'description' => 'to_stock',
				]);
				
				
				return 'credit';
			}
			
			
			return 'paid';
			
			
		}
		
		public function handle_invoice_payments($method,$payment_type = 'payment')
		{
			
			
			$this->payments()->create([
				'organization_id' => $this->organization_id,
				'creator_id' => $this->creator_id,
				'user_id' => $this->user_id,
				'chart_id' => $method['id'],
				'amount_ar_words' => Tafqeet::arablic($method['amount']),
				'amount_en_words' => Tafqeet::arablic($method['amount']),
				'amount' => $method['amount'],
				'payment_type' => $payment_type
			]);
		}
		
		public function create_tax_transaction($creator_stock,$user_id,$items = [],$expenses = [])
		{
			$tax_account = Account::where('slug','vat')->first();
			
			
			$tax = $this->to_extract_tax_from_expenses($items,$expenses) + $this->tax;
			
			
			if($tax>0)
			{
				$tax_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $tax,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'description' => 'to_tax',
				]);
			}
			
		}
		
		public function to_extract_tax_from_expenses($items = [],$expenses = [])
		{
			
			
			$total_taxes = 0;
			foreach ($expenses as $expense){
				
				foreach ($items as $item){
					$new_item = Item::find($item['id']);
					$amount = $expense['amount'] * $item['widget'] / $new_item->get_item_purchase_tax_as_value(); //
					
					$tax = $expense['amount'] - $amount;
//					var_dump($tax);
//					exit();
					$total_taxes = $total_taxes + $tax;
					
				}
				
				
			}
			return $total_taxes;
		}
	}