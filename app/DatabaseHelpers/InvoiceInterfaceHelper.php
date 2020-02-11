<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use AliAbdalla\Tafqeet\Core\Tafqeet;
	use App\Account;
	use App\InvoiceItems;
	use App\Item;
	use App\TransactionsContainer;
	
	trait InvoiceInterfaceHelper
	{
		
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
			
			
			if ($invoice_type == 'quotation'){
				if (!empty($items)){
					foreach ($items as $invoice_item){
						$fresh_item = Item::find($invoice_item['id']);
						$fresh_item->init_quotation_create_invoice_item($invoice_item,$invoice_type,$user_id,
							$sub_invoice,$expenses);
						$result[] = $fresh_item;
					}
					
					return $result;
				}
				
			}else{
				if (!empty($items)){
					
					foreach ($items as $invoice_item){
						$fresh_item = Item::find($invoice_item['id']);
						$fresh_item->init_create_invoice_item($invoice_item,$invoice_type,$user_id,$sub_invoice,$expenses);
						$result[] = $fresh_item;
					}
					
					return $result;
				}
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
		
		public function make_instance_child_invoice($invoice_type)
		{
			$child_invoice_data['total'] = 0;
			$child_invoice_data['remaining'] = 0;
			$child_invoice_data['net'] = 0;
			$child_invoice_data['subtotal'] = 0;
			$child_invoice_data['discount_value'] = 0;
			$child_invoice_data['discount_percent'] = 0;
			$child_invoice_data['organization_id'] = auth()->user()->organization_id;
			$child_invoice_data['department_id'] = $this->department_id;
			$child_invoice_data['branch_id'] = $this->branch_id;
			$child_invoice_data['organization_id'] = $this->organization_id;
			$child_invoice_data['creator_id'] = auth()->user()->id;
			$child_invoice_data['current_status'] = $this->current_status;
			$child_invoice_data['issued_status'] = $this->issued_status;
			$child_invoice_data['invoice_type'] = $invoice_type;
			return $this->child()->create($child_invoice_data);
		}
		
		public function create_return_invoice_items($items = [],$sub_invoice)
		{
			
			if (!empty($items)){
				foreach ($items as $key => $invoice_item){
					if ($invoice_item['returned_qty'] >= 1 && !$invoice_item['belong_to_kit']){
						$fresh_invoice_item = InvoiceItems::find($invoice_item['id']);
						if ($invoice_item['item']['is_kit']){
							$fresh_invoice_item->init_return_kit($invoice_item['returned_qty'],$sub_invoice);
						}else{
							$fresh_invoice_item->init_return_item($invoice_item,$sub_invoice);
						}
						
						
					}
				}
			}
			
		}
		
		public function update_return_invoice_data()
		{
			
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount_value'] = 0;
			$result['net'] = 0;
			
			$result['is_updated'] = true;
			$result['is_deleted'] = true;
			
			
			$items = $this->items;
			foreach ($items as $item){
				$result['total'] = $result['total'] + $item['total'];
				$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
				$result['tax'] = $result['tax'] + $item['tax'];
				$result['discount_value'] = $result['discount_value'] + $item['discount'];
				$result['net'] = $result['net'] + $item['net'];
				if ($item['qty'] != $item['r_qty']){
					$result['is_deleted'] = false;
				}
			}
			
			$paid = 0;
			foreach ($this->payments as $payment){
				$paid += $payment['amount'];
			}
			
			$result['remaining'] = $result['net'] - $paid;
			
			
			$this->update($result);
			
			$this->update_invoice_totals_data();
			return $result;
		}
		
		public function update_invoice_totals_data()
		{
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount_value'] = 0;
			$result['net'] = 0;
			
			
			$items = $this->items;
			foreach ($items as $item){
				if (!$item->item->is_kit){
					$result['total'] = $result['total'] + $item['total'];
					$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
					$result['tax'] = $result['tax'] + $item['tax'];
					$result['discount_value'] = $result['discount_value'] + $item['discount'];
					$result['net'] = $result['net'] + $item['net'];
				}
				
				
			}
			
			$paid = 0;
			foreach ($this->payments as $payment){
				$paid += $payment['amount'];
			}
			
			$result['remaining'] = $result['net'] - $paid;
			
			
			$this->update($result);
			
		}
		
		public function update_invoice_sale_totals_data()
		{
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount_value'] = 0;
			$result['net'] = 0;
			
			
			$items = $this->items;
			foreach ($items as $item){
				if (!$item->item->is_kit){
					if (!$item['belong_to_kit']){
						$result['total'] = $result['total'] + $item['total'];
						$result['discount_value'] = $result['discount_value'] + $item['discount'];
					}
					
					
					$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
					$result['tax'] = $result['tax'] + $item['tax'];
					
					$result['net'] = $result['net'] + $item['net'];
				}
				
				
			}
			
			$paid = 0;
			foreach ($this->payments as $payment){
				$paid += $payment['amount'];
			}
			
			$result['remaining'] = $result['net'] - $paid;
			
			
			$this->update($result);
			
		}
		
		public function update_invoice_creation_status($status)
		{
			$this->update([
				'issued_status' => $status,
				'current_status' => $status,
			]);
		}
		
		public function handle_invoice_transactions($methods = [],$user_id,$net,$items,$expenses,$invoice_type = 'purchase')
		{
			
			
			$container = new  TransactionsContainer();
			$container->creator_id = auth()->user()->id;
			$container->organization_id = auth()->user()->organization_id;
			$container->amount = 0;
			$container->description = 'invoice';
			$container->invoice_id = $this->id;
			$container->save();
			
			$container_id = $container->id;
			if ($invoice_type == 'purchase'){
				return $this->handle_purchase_transactions($methods,$user_id,$net,$items,$expenses,$container_id);
			}elseif ($invoice_type == 'r_purchase'){
				return $this->handle_return_purchase_transactions($methods,$user_id,$net,$items,$expenses,$container_id);
			}elseif ($invoice_type == 'sale'){
				return $this->handle_sale_transactions($methods,$user_id,$net,$items,$expenses,$container_id);
			}elseif ($invoice_type == 'r_sale'){
				return $this->handle_return_sale_transactions($methods,$user_id,$net,$items,$expenses,$container_id);
			}

//			$container->update_amount();
			
			return 'paid';
		}
		
		public function handle_purchase_transactions($methods = [],$user_id,$net,$items,$expenses,$container_id)
		{
			$creator_stock = auth()->user()->manager_current_stock();
			$paid_amount = 0;
			
			
			$vendor_account = auth()->user()->get_active_manager_account_for('vendors');
			
			$vendor_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $this->id,
				'container_id' => $container_id,
				'description' => 'vendor_balance'
			]);
			
			
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
						'container_id' => $container_id,
						'description' => 'to_stock',
					]);
					
					
					$vendor_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'vendor_balance'
					]);
//
					
					$this->handle_invoice_payments($method,'payment',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			} // 50
			
			// 450 //  500 - 50
			
			
			$this->create_tax_transaction($creator_stock,$user_id,$items,$expenses,$container_id);
			
			
			if ($paid_amount < $net){
				
				$amount = floatval($net) - floatval($paid_amount);
				
				$this->user()->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);

//
				$this->user()->update_vendor_balance('add',$amount);
				return 'credit';
			}
			
			
			return 'paid';
		}
		
		public function handle_invoice_payments($method,$payment_type = 'payment',$gateway = null)
		{
			
			if ($gateway != null){
				
				$payment = $gateway->paymentable()->create([
					'organization_id' => $this->organization_id,
					'creator_id' => $this->creator_id,
					'user_id' => $this->user_id,
					'invoice_id' => $this->id,
					'amount_ar_words' => Tafqeet::arablic(money_format("%i",$method['amount'])),
					'amount_en_words' => Tafqeet::arablic(money_format("%i",$method['amount'])),
					'amount' => $method['amount'],
					'payment_type' => $payment_type
				]);
			}else{
				$payment = $this->payments()->create([
					'organization_id' => $this->organization_id,
					'creator_id' => $this->creator_id,
					'user_id' => $this->user_id,
					'invoice_id' => $this->id,
					'amount_ar_words' => Tafqeet::arablic(money_format("%i",$method['amount'])),
					'amount_en_words' => Tafqeet::arablic(money_format("%i",$method['amount'])),
					'amount' => $method['amount'],
					'payment_type' => $payment_type
				]);
			}
			
			
			$this->invoice_payments()->create([
				'organization_id' => $this->organization_id,
				'creator_id' => $this->creator_id,
				'payment_id' => $payment->id,
				'amount' => $method['amount'],
			]);
//			}
			
			
		}
		
		public function create_tax_transaction($creator_stock,$user_id,$items = [],$expenses = [],$container_id)
		{
			
			
			$tax_account = Account::where('slug','vat')->first();
			$this->make_invoice_expenses($items,$expenses);
			
			$expenses_tax = $this->expenses()->sum('tax');
			
			
			$tax = $expenses_tax + $this->tax;
			
			
			if (in_array($this->invoice_type,['sale','r_purchase'])){
				
				
				if ($tax > 0){
					$tax_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'debitable_id' => $creator_stock->id,
						'debitable_type' => get_class($creator_stock),
						'amount' => $tax,
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'to_tax',
					]);
				}
				
				
				$sum = $this->expenses()->where('with_net',0)->sum('amount');
				
				
				if ($sum > 0){
					$manager_cash_account = auth()->user()->manager_gateway('cash');
					
					
					$tax_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'creditable_id' => $manager_cash_account->id,
						'creditable_type' => get_class($manager_cash_account),
						'amount' => $sum,
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
				}
				
				
				return;
			}


//			dd($this->tax);
			
			
			if ($tax > 0){
				$tax_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $tax,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_tax',
				]);
			}
			
			
			$sum = $this->expenses()->where('with_net',0)->sum('amount');
			
			
			// create transaction or update cash transaction amount where there is any unincluded expenses
			if ($sum > 0){
				$manager_cash_account = auth()->user()->manager_gateway('cash');
				$cash_paid_before = $this->transactions()->where([['creditable_type','App\Account'],['creditable_id',
					$manager_cash_account->id]])->first();
				
				
				if (!empty($cash_paid_before)){
					$new_amount = $cash_paid_before->amount + $sum;
					$cash_paid_before->update([
						'amount' => $new_amount
					]);
				}else{
					$tax_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'creditable_id' => $manager_cash_account->id,
						'creditable_type' => get_class($manager_cash_account),
						'amount' => $sum,
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
				}
				
			}
			
		}
		
		public function make_invoice_expenses($items = [],$expenses = [])
		{
			
			
			$total_taxes = 0;
			foreach ($expenses as $expense){
				
				foreach ($items as $item){
					$new_item = Item::find($item['id']);
					$amount = $expense['amount'] * $item['widget'] / $new_item->get_item_purchase_tax_as_value(); //
					
					$tax = $expense['amount'] - $amount;
					
					$total_taxes = $total_taxes + $tax;
					
					
				}
				
				
				$org_vat = auth()->user()->organization->organization_vat;
				$expense_tax = $expense['amount'] * $org_vat / (100 + $org_vat);
				
				$this->expenses()->create(
					[
						'expense_id' => $expense['id'],
						'amount' => $expense['amount'],
						'tax' => $expense_tax,
						'with_net' => $expense['is_apended_to_net'],
					]
				);
				
				
			}
			return $total_taxes;
		}
		
		public function handle_return_purchase_transactions(
			$methods = [],$user_id,$net,$items,$expenses,
			$container_id)
		{
			$creator_stock = auth()->user()->manager_current_stock();
			$paid_amount = 0;
			
			
			$vendor_account = auth()->user()->get_active_manager_account_for('vendors');
			
			$vendor_account->debit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $this->id,
				'container_id' => $container_id,
				'description' => 'vendor_balance'
			]);
			
			
			foreach ($methods as $method){
				
				if ($method['amount'] > 0){
					
					$gateway = Account::find($method['id']);
					
					$gateway->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'creditable_id' => $creator_stock->id,
						'creditable_type' => get_class($creator_stock),
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					
					
					$vendor_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'vendor_balance'
					]);
					
					
					$this->handle_invoice_payments($method,'receipt',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			
			$this->create_tax_transaction($creator_stock,$user_id,$items,$expenses,$container_id);
			
			
			if ($paid_amount < $net){
				
				
				$amount = floatval($net) - floatval($paid_amount);
				$this->user()->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				$this->user()->update_vendor_balance('sub',$amount);
				
				return 'credit';
			}
			
			
			return 'paid';
		}
		
		public function handle_sale_transactions($methods = [],$user_id,$net,$items,$expenses,$container_id)
		{
			$creator_stock = auth()->user()->manager_current_stock();
			$paid_amount = 0;
			
			
			$client_account = auth()->user()->get_active_manager_account_for('clients');
			
			$client_account->debit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $this->id,
				'container_id' => $container_id,
				'description' => 'client_balance'
			]);
			
			
			foreach ($methods as $method){
				
				if ($method['amount'] > 0){
					
					$gateway = Account::find($method['id']);
					
					$gateway->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'creditable_id' => $creator_stock->id,
						'creditable_type' => get_class($creator_stock),
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					
					
					$client_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'client_balance'
					]);
					
					
					$this->handle_invoice_payments($method,'receipt',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			
			$this->create_tax_transaction($creator_stock,$user_id,$items,$expenses,$container_id);
			
			
			$is_credit = false;
			if ($paid_amount < $net){
				
				
				$amount = floatval($net) - floatval($paid_amount);
				$this->user()->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				
				$this->user()->update_client_balance('add',$amount);
				
				$is_credit = true;
			}
			
			
			/*
			 * to transaction for cost of goods
			 *
			 * */

//
			$manager_cogs_account = auth()->user()->get_active_manager_account_for('cogs');
			$manager_products_sales_account = auth()->user()->get_active_manager_account_for('products_sales');
			$manager_services_sales_account = auth()->user()->get_active_manager_account_for('services_sales');
			$manager_other_services_sales_account = auth()->user()->get_active_manager_account_for('other_services_sales');
			
			
			$manager_products_sales_discount_account = auth()->user()->get_active_manager_account_for('products_sales_discount');
			$manager_services_sales_discount_account = auth()->user()->get_active_manager_account_for('services_sales_discount');
			$manager_other_services_sales_discount_account = auth()->user()->get_active_manager_account_for('other_services_sales_discount');
			
			
			$manager_stock_account = auth()->user()->get_active_manager_account_for('stock');
//			$invoice_items = $this->items;
			$total_cost = $this->transactions()->where('description','to_item')->sum('amount');
			// to make cost of goods transaction
			if ($total_cost > 0){
				$manager_cogs_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $total_cost,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_cogs',
				]);
			}
			
			
			// to make sales transactions
			$products_sales_total = 0;
			$services_sales_total = 0;
			$other_services_sales_total = 0;
			
			$products_sales_total_discount = 0;
			$services_sales_total_discount = 0;
			$other_services_sales_total_discount = 0;
			
			
			foreach ($items as $item){
				if ($item['item']['is_expense']){
					$other_services_sales_total = $other_services_sales_total + $item['total'];
					$other_services_sales_total_discount = $other_services_sales_total_discount + $item['discount'];
				}else if ($item['item']["is_service"]){
					$services_sales_total = $services_sales_total + $item['total'];
					$services_sales_total_discount = $services_sales_total_discount + $item['discount'];
				}else{
					$products_sales_total = $products_sales_total + $item['total'];
					$products_sales_total_discount = $products_sales_total_discount + $item['discount'];
				}
			}
			
			
			if ($products_sales_total > 0){
				$manager_products_sales_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $products_sales_total,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_products_sales',
				]);
			}
			
			if ($services_sales_total > 0){
				$manager_services_sales_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $services_sales_total,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_services_sales',
				]);
			}
			
			if ($other_services_sales_total > 0){
				$manager_other_services_sales_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $other_services_sales_total,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_other_services_sales',
				]);
			}
			
			
			/// discounts
			if ($products_sales_total_discount > 0){
				$manager_products_sales_discount_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $products_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_products_sales_discount',
				]);
			}
			
			if ($services_sales_total_discount > 0){
				$manager_services_sales_discount_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $services_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_services_sales_discount',
				]);
			}
			
			if ($other_services_sales_total_discount > 0){
				$manager_other_services_sales_discount_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $other_services_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_other_services_sales_discount',
				]);
			}

//
			
			
			if ($is_credit)
				return 'credit';
			
			return 'paid';
		}
		
		public function handle_return_sale_transactions($methods = [],$user_id,$net,$items,$expenses,$container_id)
		{
			$creator_stock = auth()->user()->manager_current_stock();
			$paid_amount = 0;
			
			$client_account = auth()->user()->get_active_manager_account_for('clients');
			
			$client_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $this->id,
				'container_id' => $container_id,
				'description' => 'client_balance'
			]);
			
			
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
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					
					$client_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $this->id,
						'container_id' => $container_id,
						'description' => 'client_balance'
					]);
					
					
					$this->handle_invoice_payments($method,'payment',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			
			$this->create_tax_transaction($creator_stock,$user_id,$items,$expenses,$container_id);
			
			
			$is_credit = false;
			if ($paid_amount < $net){
				
				
				$amount = floatval($net) - floatval($paid_amount);
				$this->user()->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				$this->user()->update_client_balance('sub',$amount);
				
				$is_credit = true;
			}
			
			
			/*
			 * to transaction for cost of goods
			 *
			 * */

//
			$manager_cogs_account = auth()->user()->get_active_manager_account_for('cogs');
			$manager_products_sales_return_account = auth()->user()->get_active_manager_account_for('products_return_sales');
			$manager_services_sales_return_account = auth()->user()->get_active_manager_account_for('services_return_sales');
			//$manager_other_services_sales_return_account = auth()->user()->get_active_manager_account_for
			//('other_services_return_sales');
			
			
			$manager_products_sales_discount_account = auth()->user()->get_active_manager_account_for('products_sales_discount');
			$manager_services_sales_discount_account = auth()->user()->get_active_manager_account_for('services_sales_discount');
			$manager_other_services_sales_discount_account = auth()->user()->get_active_manager_account_for('other_services_sales_discount');
			
			
			$manager_stock_account = auth()->user()->get_active_manager_account_for('stock');
//			$invoice_items = $this->items;
			$total_cost = $this->transactions()->where('description','to_item')->sum('amount');
			// to make cost of goods transaction
			$manager_cogs_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'debitable_id' => $manager_stock_account->id,
				'debitable_type' => get_class($manager_stock_account),
				'amount' => $total_cost,
				'user_id' => $user_id,
				'invoice_id' => $this->id,
				'container_id' => $container_id,
				'description' => 'to_cogs',
			]);
			
			
			// to make sales transactions
			$products_sales_total = 0;
			$services_sales_total = 0;
			$other_services_sales_total = 0;
			
			$products_sales_total_discount = 0;
			$services_sales_total_discount = 0;
			$other_services_sales_total_discount = 0;
			
			
			foreach ($items as $item){
				if ($item['item']['is_expense']){
					$other_services_sales_total = $other_services_sales_total + $item['total'];
					$other_services_sales_total_discount = $other_services_sales_total_discount + $item['discount'];
				}else if ($item['item']['is_service']){
					$services_sales_total = $services_sales_total + $item['total'];
					$services_sales_total_discount = $services_sales_total_discount + $item['discount'];
				}else{
					$products_sales_total = $products_sales_total + $item['total'];
					$products_sales_total_discount = $products_sales_total_discount + $item['discount'];
				}
			}
			
			
			if ($products_sales_total > 0){
				$manager_products_sales_return_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $products_sales_total,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_products_sales',
				]);
			}
			
			if ($services_sales_total > 0){
				$manager_services_sales_return_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $services_sales_total,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_services_sales',
				]);
			}

//			if ($other_services_sales_total > 0){
//				$manager_other_services_sales_return_account->debit_transaction()->create([
//					'creator_id' => auth()->user()->id,
//					'organization_id' => auth()->user()->organization_id,
//					'creditable_id' => $manager_stock_account->id,
//					'creditable_type' => get_class($manager_stock_account),
//					'amount' => $other_services_sales_total,
//					'user_id' => $user_id,
//					'invoice_id' => $this->id,
//					'description' => 'to_other_services_sales',
//				]);
//			}
			
			
			/// discounts
			if ($products_sales_total_discount > 0){
				$manager_products_sales_discount_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $products_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_products_sales_discount',
				]);
			}
			
			if ($services_sales_total_discount > 0){
				$manager_services_sales_discount_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $services_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_services_sales_discount',
				]);
			}
			
			if ($other_services_sales_total_discount > 0){
				$manager_other_services_sales_discount_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $other_services_sales_total_discount,
					'user_id' => $user_id,
					'invoice_id' => $this->id,
					'container_id' => $container_id,
					'description' => 'to_other_services_sales_discount',
				]);
			}

//
			
			
			if ($is_credit)
				return 'credit';
			
			return 'paid';
		}
		
		public function get_total_expenses($expense)
		{
			$total = 0;
			foreach ($expense as $expen){
				$total = $total + $expen['amount'];
			}
			return $total;
		}
		
	}