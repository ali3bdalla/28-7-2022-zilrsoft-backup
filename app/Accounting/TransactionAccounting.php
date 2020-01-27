<?php
	
	
	namespace App\Accounting;
	
	
	use App\Account;
	use App\Invoice;
	use App\InvoiceItems;
	use App\TransactionsContainer;
	use Illuminate\Support\Carbon;
	
	trait TransactionAccounting
	{
		
		/**
		 * @param $amount
		 * @param $transaction_container_id
		 * @param int $shortage
		 */
		public function toCreateManagerCloseAccountTransaction($amount,$transaction_container_id,$shortage = 0)
		{
			$manager = auth()->user();
			
			$lastDate = $this->toGetFirstSaleInvoiceDateAfterLastAccountClose();
			
			$manager->private_transactoins()->create([
				'organization_id' => auth()->user()->organization_id,
				'transaction_type' => "close_account",
				'transaction_container_id' => $transaction_container_id,
				'close_account_start_date' => $lastDate,
				'close_account_end_date' => now(),
				'amount' => $amount,
				'shortage_amount' => $shortage,
			]);
		}
		
		/**
		 * @return string
		 */
		public function toGetFirstSaleInvoiceDateAfterLastAccountClose()
		{
			$manager = auth()->user();
			
			$lastAccountCloseTransaction = $manager->private_transactoins()->where([
				['creator_id',$manager->id],
				['transaction_type',"close_account"],
			])->orderBy('id','desc')->first();
			
			
			$startTransactionDate = now();
			
			if (!empty($lastAccountCloseTransaction) && $lastAccountCloseTransaction->close_account_end_date != null){
				$startTransactionDate = $lastAccountCloseTransaction->close_account_end_date;
			}
			
			
			$lastInvoice = Invoice::where([
				['invoice_type','sale'],
				['creator_id',$manager->id],
				['created_at',">",$startTransactionDate],
			])->first();
			
			
			return empty($lastInvoice) ? Carbon::today()->subMonths(12)->toDateTimeString() : $lastInvoice->created_at;
		}
		
		public function toGetLastManagerTransferRemainingAmount()
		{
			$dailyAccount = Account::where([
				['slug','temp_reseller_account'],
				['is_system_account',true],
			])->first();
			
			$lastTransferRemainingAmount = 0;
			
			$lastAccountCloseTransaction = auth()->user()->private_transactoins()->where([
				['creator_id',auth()->user()->id],
			
			])->orderBy('id','desc')->first();


//			return $dailyAccount->debit_transaction;
			
			if (!empty($lastAccountCloseTransaction) && $lastAccountCloseTransaction->transaction_type == "transfer"){
				$lastDebit = $dailyAccount->debit_transaction()->where([
					['container_id',$lastAccountCloseTransaction->transaction_container_id]
				])->first();
				if (!empty($lastDebit)){
					$lastTransferRemainingAmount = $lastDebit->amount;
				}
			}
			
			
			return $lastTransferRemainingAmount;
		}
		
		/**
		 * @param Invoice $inc
		 * @param array $items
		 * @param array $methods
		 * @param array $expenses
		 *
		 * @return Invoice
		 */
		public function toCreateInvoiceTransactions(Invoice $inc,$items = [],$methods = [],$expenses = [])
		{
			
			$container_id = $this->toCreateTransactionsContainer($inc);
			
			$items = $inc->items()->where('is_Kit',false)->get();
			
			if ($inc->invoice_type == 'purchase'){
				$this->toCreatePurchasesTransactions($inc,$items,$methods,$expenses,$container_id);
			}elseif ($inc->invoice_type == 'r_purchase'){
				$this->toCreatePurchasesReturnTransactions($inc,$items,$methods,$expenses,$container_id);
			}elseif ($inc->invoice_type == 'sale'){
				$this->toCreateSalesTransactions($inc,$items,$methods,$expenses,$container_id);
			}elseif ($inc->invoice_type == 'r_sale'){
				$this->toCreateSalesReturnTransactions($inc,$items,$methods,$expenses,$container_id);
			}
			
			$this->toUpdateRemainingAmount($inc);
			return $inc;
//
		}
		
		/**
		 * @param $inc
		 *
		 * @return mixed
		 */
		private function toCreateTransactionsContainer(Invoice $inc)
		{
			$container = new  TransactionsContainer();
			$container->creator_id = auth()->user()->id;
			$container->organization_id = auth()->user()->organization_id;
			$container->amount = 0;
			$container->description = 'invoice';
			$container->invoice_id = $inc->id;
			$container->save();
			return $container->id;
		}
		
		/**
		 * @param $inc
		 * @param array $methods
		 * @param $expenses
		 * @param $container_id
		 */
		private function toCreatePurchasesTransactions(Invoice $inc,$items,$methods = [],$expenses,$container_id)
		{
			$gateways_paid_amounts = 0;
			$creator_stock = auth()->user()->toGetManagerAccount('stock');
			$vendor_account = auth()->user()->toGetManagerAccount('vendors');
			$vendor_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $inc->net,
				'user_id' => $inc->user_id,
				'invoice_id' => $inc->id,
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
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_stock',
					]);
					$vendor_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'vendor_balance'
					]);
					$this->toCreateInvoicePayment($inc,$method['amount'],'payment',$gateway);
					$gateways_paid_amounts = $gateways_paid_amounts + $method['amount'];
				}
			}
			
			$this->toCreateInvoiceTaxTransactions($inc,$creator_stock,$items,$expenses,$container_id);
			
			if ($gateways_paid_amounts < $inc->net){
				$amount = floatval($inc->net) - floatval($gateways_paid_amounts);
				$inc->user()->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				$this->toUpdateVendorBalance($inc->user(),'plus',$amount);
			}
		}
		
		/**
		 * @param $inc
		 * @param $creator_stock
		 * @param $items
		 * @param $expenses
		 * @param $container_id
		 */
		private function toCreateInvoiceTaxTransactions(Invoice $inc,$creator_stock,$items,$expenses,$container_id)
		{
			
			$tax_account = Account::where('slug','vat')->first();
			$this->toCreateInvoiceExpenseAndGetTotal($inc,$items,$expenses);
			$expenses_tax = $inc->expenses()->sum('tax');
			$tax = $expenses_tax + $inc->tax;
			
			if (in_array($inc->invoice_type,['sale','r_purchase'])){
				if ($tax > 0){
					$tax_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'debitable_id' => $creator_stock->id,
						'debitable_type' => get_class($creator_stock),
						'amount' => $tax,
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_tax',
					]);
				}
				$sum = $inc->expenses()->where('with_net',0)->sum('amount');
				if ($sum > 0){
					$manager_cash_account = $temp_reseller_account = Account::where([
						['is_system_account',true],
						['slug','temp_reseller_account'],
					])->first();
					$tax_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'creditable_id' => $manager_cash_account->id,
						'creditable_type' => get_class($manager_cash_account),
						'amount' => $sum,
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
				}
				return;
			}
			
			
			if ($tax > 0){
				$tax_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $tax,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_tax',
				]);
			}
			$sum = $inc->expenses()->where('with_net',0)->sum('amount');
			if ($sum > 0){
				$manager_cash_account = $temp_reseller_account = Account::where([
					['is_system_account',true],
					['slug','temp_reseller_account'],
				])->first();
				$cash_paid_before = $inc->transactions()->where([['creditable_type','App\Account'],['creditable_id',
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
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
				}
				
			}
			
			
		}
		
		/**
		 * @param $inc
		 * @param $items
		 * @param $methods
		 * @param $expenses
		 * @param $container_id
		 */
		private function toCreatePurchasesReturnTransactions(Invoice $inc,$items,$methods,$expenses,$container_id)
		{
			$creator_stock = auth()->user()->toGetManagerAccount('stock');
			$vendor_account = auth()->user()->toGetManagerAccount('vendors');
			$paid_amount = 0;
			
			$vendor_account->debit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $inc->net,
				'user_id' => $inc->user_id,
				'invoice_id' => $inc->id,
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
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					
					
					$vendor_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $inc->user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'vendor_balance'
					]);
					$this->toCreateInvoicePayment($inc,$method['amount'],'receipt',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			
			$this->toCreateInvoiceTaxTransactions($inc,$creator_stock,$items,$expenses,$container_id);
			
			
			if ($paid_amount < $inc->net){
				
				
				$amount = floatval($inc->net) - floatval($paid_amount);
				$inc->user()->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				$this->toUpdateVendorBalance($inc->user(),'sub',$amount);
				
			}
			
			
		}
		
		/**
		 * @param $inc
		 * @param $items
		 * @param array $methods
		 * @param $expenses
		 * @param $container_id
		 *
		 * @return string
		 */
		private function toCreateSalesTransactions(Invoice $inc,$items,$methods = [],$expenses,$container_id)
		{
			$creator_stock = auth()->user()->toGetManagerAccount('stock');
			$client_account = auth()->user()->toGetManagerAccount('clients');
			
			$net = $inc->net;
			$user_id = $inc->user_id;
			
			$gateways_total_paid = 0;
			$client_account->debit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $inc->id,
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
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					$client_account->credit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'client_balance'
					]);
					$this->toCreateInvoicePayment($inc,$method['amount'],'receipt',$gateway);
					$gateways_total_paid = $gateways_total_paid + $method['amount'];
				}
				
				
			}
			
			
			$this->toCreateInvoiceTaxTransactions($inc,$creator_stock,$items,$expenses,$container_id);
			if ($gateways_total_paid < $net){
				$amount = floatval($net) - floatval($gateways_total_paid);
				$inc->user()->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				
				$this->toUpdateClientBalance($inc->user(),'plus',$amount);
			}
			
			$this->toCreateSalesCostTransacations($inc,$items,$container_id);
		}
		
		/**
		 * @param $inc
		 * @param $items
		 * @param $container_id
		 */
		public function toCreateSalesCostTransacations(Invoice $inc,$items,$container_id)
		{
			
			$manager_cogs_account = auth()->user()->toGetManagerAccount('cogs');
			$manager_products_sales_account = auth()->user()->toGetManagerAccount('products_sales');
			$manager_services_sales_account = auth()->user()->toGetManagerAccount('services_sales');
			$manager_other_services_sales_account = auth()->user()->toGetManagerAccount('other_services_sales');
			$manager_products_sales_discount_account = auth()->user()->toGetManagerAccount('products_sales_discount');
			$manager_services_sales_discount_account = auth()->user()->toGetManagerAccount('services_sales_discount');
			$manager_other_services_sales_discount_account = auth()->user()->toGetManagerAccount('other_services_sales_discount');
			$manager_stock_account = auth()->user()->toGetManagerAccount('stock');
			
			
			$total_cost = $inc->transactions()->where('description','to_item')->sum('amount');
			
			if ($total_cost > 0){
				$manager_cogs_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $total_cost,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->uesr_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_other_services_sales_discount',
				]);
			}
			
		}
		
		/**
		 * @param $inc
		 * @param $items
		 * @param $methods
		 * @param $expenses
		 * @param $container_id
		 */
		private function toCreateSalesReturnTransactions(Invoice $inc,$items,$methods,$expenses,$container_id)
		{
			$creator_stock = auth()->user()->toGetManagerAccount('stock');
			$client_account = auth()->user()->toGetManagerAccount('clients');
			
			$paid_amount = 0;
			$user_id = $inc->user_id;
			$net = $inc->net;
			
			$client_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'amount' => $net,
				'user_id' => $user_id,
				'invoice_id' => $inc->id,
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
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'to_gateway',
					]);
					$client_account->debit_transaction()->create([
						'creator_id' => auth()->user()->id,
						'organization_id' => auth()->user()->organization_id,
						'amount' => $method['amount'],
						'user_id' => $user_id,
						'invoice_id' => $inc->id,
						'container_id' => $container_id,
						'description' => 'client_balance'
					]);
					
					
					$this->toCreateInvoicePayment($inc,$method['amount'],'payment',$gateway);
					$paid_amount = $paid_amount + $method['amount'];
				}
				
				
			}
			
			$this->toCreateInvoiceTaxTransactions($inc,$creator_stock,$items,$expenses,$container_id);
			
			if ($paid_amount < $net){
				
				$amount = floatval($net) - floatval($paid_amount);
				$inc->user()->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_stock',
				]);
				
				$this->toUpdateClientBalance($inc->user(),'sub',$amount);
				
			}
			
			$this->toCreateSalesReturnCostTransacations($inc,$items,$container_id);
		}
		
		/**
		 * @param $inc
		 * @param $items
		 * @param $container_id
		 */
		private function toCreateSalesReturnCostTransacations(Invoice $inc,$items,$container_id)
		{
			$manager_cogs_account = auth()->user()->toGetManagerAccount('cogs');
			$manager_products_sales_return_account = auth()->user()->toGetManagerAccount('products_return_sales');
			$manager_services_sales_return_account = auth()->user()->toGetManagerAccount('services_return_sales');
			$manager_other_services_sales_return_account = auth()->user()->toGetManagerAccount
			('other_services_return_sales');
			$manager_products_sales_discount_account = auth()->user()->toGetManagerAccount('products_sales_discount');
			$manager_services_sales_discount_account = auth()->user()->toGetManagerAccount('services_sales_discount');
			$manager_other_services_sales_discount_account = auth()->user()->toGetManagerAccount('other_services_sales_discount');
			$manager_stock_account = auth()->user()->toGetManagerAccount('stock');
//			$invoice_items = $this->items;
			$total_cost = $inc->transactions()->where('description','to_item')->sum('amount');
			// to make cost of goods transaction
			$manager_cogs_account->credit_transaction()->create([
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'debitable_id' => $manager_stock_account->id,
				'debitable_type' => get_class($manager_stock_account),
				'amount' => $total_cost,
				'user_id' => $inc->user_id,
				'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_products_sales',
				]);
			}
//			$manager_other_services_sales_return_account
			if ($services_sales_total > 0){
				$manager_services_sales_return_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $services_sales_total,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_services_sales',
				]);
			}
			
			if ($other_services_sales_total > 0){
				$manager_other_services_sales_return_account->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $manager_stock_account->id,
					'creditable_type' => get_class($manager_stock_account),
					'amount' => $other_services_sales_total,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'description' => 'to_other_services_sales',
				]);
			}
			
			
			/// discounts
			if ($products_sales_total_discount > 0){
				$manager_products_sales_discount_account->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $manager_stock_account->id,
					'debitable_type' => get_class($manager_stock_account),
					'amount' => $products_sales_total_discount,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
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
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'container_id' => $container_id,
					'description' => 'to_other_services_sales_discount',
				]);
			}

//
		
		}
		
		/**
		 * @param InvoiceItems $incItem
		 * @param $inc
		 * @param $expenses
		 */
		public function toCreateIncItemTransaction(InvoiceItems $incItem,Invoice $inc,$expenses = 0)
		{
			if ($incItem->item->is_service && $incItem->item->is_kit)
				return;
			
			
			$creator_stock = auth()->user()->toGetManagerAccount('stock');
			if (in_array($inc->invoice_type,['purchase','beginning_inventory'])){
				$incItem->item->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $incItem->subtotal + $expenses,
					'user_id' => $inc->user_id,
					'invoice_id' => $incItem->invoice_id,
					'description' => 'to_item',
				]);
				
				
			}elseif ($inc->invoice_type == 'r_purchase'){
				
				$incItem->item->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $incItem->subtotal,
					'user_id' => $inc->user_id,
					'invoice_id' => $incItem->invoice_id,
					'description' => 'to_item',
				]);
				
			}else if ($inc->invoice_type == 'sale'){
				
				$amount = $incItem->item->cost * $incItem->qty;
//
//				if($incItem->item->is_expense)
//				{
//
//					$old_amount = $amount;
//					$amount = $old_amount / (1+($incItem->item->vts / 100));
//					$amount = 1;
//				}
				
				
				$incItem->item->credit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'debitable_id' => $creator_stock->id,
					'debitable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $inc->user_id,
					'invoice_id' => $incItem->invoice_id,
					'description' => 'to_item',
				]);
				
			}else if ($inc->invoice_type == 'r_sale'){
				$amount = $incItem->item->cost * $incItem->qty;
				$incItem->item->debit_transaction()->create([
					'creator_id' => auth()->user()->id,
					'organization_id' => auth()->user()->organization_id,
					'creditable_id' => $creator_stock->id,
					'creditable_type' => get_class($creator_stock),
					'amount' => $amount,
					'user_id' => $inc->user_id,
					'invoice_id' => $incItem->invoice_id,
					'description' => 'to_item',
				]);
				
			}
			
		}
		
	}