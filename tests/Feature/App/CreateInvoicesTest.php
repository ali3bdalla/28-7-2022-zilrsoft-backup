<?php
	
	namespace Tests\Feature\App;
	
	use App\Jobs\Time\Invoice\UpdateInvoiceCreatedAtJob;
	use App\Models\Account;
	use App\Models\Invoice;
	use App\Models\Item;
	use App\Models\ItemSerials;
	use App\Models\KitItems;
	use App\Models\Manager;
	use Carbon\Carbon;
	use Illuminate\Database\ConnectionInterface;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Storage;
	use Tests\TestCase;
	
	class CreateInvoicesTest extends TestCase
	{
		
		private $needManualRefactoringInvoices = [];
		
		private $updatedItems = [];
		
		/**
		 * @var ConnectionInterface
		 */
		private $dbConnection;
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function create_services_invoices()
		{
			
			$services = $this->dbConnection->table('items')->where('is_service', true)->get();
			
			foreach($services as $service) {
				$invociesServices = $this->dbConnection->table('invoice_items')->where(
					[
						['item_id', $service->id],
						['invoice_type', 'sale'],
					]
				)->get();
				
				foreach($invociesServices as $invoiceItem) {
					$invoice = $this->dbConnection->table('invoices')->find($invoiceItem->invoice_id);
					$newDBItem = Item::find($service->id);
					if($invoice != null) {
						$this->updateItemDetails($newDBItem, $invoice);
						
						$newInvoiceId = $invoice->new_db_id;
						if($newInvoiceId != null) {
							$newInvoice = Invoice::find($newInvoiceId);
							if($newInvoice != null) {
								$dbInstance = $newInvoice->items()->where('item_id', $service->id)->first();
								if($dbInstance != null) {
									continue;
								}
							}
							
						}
						// echo "should created {$newInvoiceId} - {$service->barcode} \n";
						$tempResellerAccount = Account::where('slug', 'temp_reseller_account')->first()->toArray();
						$tempResellerAccount['amount'] = $invoiceItem->net;
						$manager = Manager::find(1);
						
						$response = $this->actingAs($manager)->postJson(
							'/api/sales', [
								'items' => [
									[
										'id' => $service->id,
										'price' => $invoiceItem->price,
										'qty' => $invoiceItem->qty,
										'discount' => $invoiceItem->discount,
									],
								],
								'client_id' => 2,
								'salesman_id' => 1,
								'methods' => [$tempResellerAccount],
							]
						);
						$response
							->dump()->assertCreated();
						$id = json_decode($response->content(), true)['id'];
						
						dispatch(new UpdateInvoiceCreatedAtJob($id, $invoice->created_at));
						
						$this->dbConnection->table('invoices')->where('id', $invoice->id)->update(
							[
								'new_db_id' => $id,
							]
						);
						
						$this->restUpdatedItemsDetails();
						
						
					}
					
					// echo $createdInstance;
					
					// dd( $invoice);
				}
				// dd( $invociesServices );
				
			}
			
		}
		
		public function updateItemDetails(Item $dbItem, $invoice)
		{
			
			if(Carbon::parse($invoice->created_at)->lte(Carbon::parse('30-06-2020'))) {
				$dbItem->update(
					[
						'vts' => 5,
						'vtp' => 5,
					]
				);
				
				$this->updatedItems[] = $dbItem;
			}
			
		}
		
		public function restUpdatedItemsDetails()
		{
			foreach($this->updatedItems as $item) {
				$item->update(
					[
						'vts' => 15,
						'vtp' => 15,
					]
				);
			}
			
			$this->updatedItems = [];
		}
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function create_invoices()
		{
			
			$invoices = $this->dbConnection->table('invoices')->where('id', '>', 23006)->get();
			
			foreach($invoices as $invoice) {
				echo "\nstarting.......................\n";
				echo "source id " . $invoice->id . "\n";
				echo 'source type ' . $invoice->invoice_type . "\n";
				$createdInvoiceId = 0;
				
				if($invoice->invoice_type == 'beginning_inventory') {
					$createdInvoiceId = $this->createBeginningInventory($invoice);
				} elseif($invoice->invoice_type == 'purchase') {
					$createdInvoiceId = $this->createPurchase($invoice);
				} elseif($invoice->invoice_type == 'r_purchase') {
					$createdInvoiceId = $this->createReturnPurchase($invoice);
				} elseif($invoice->invoice_type == 'sale') {
					$createdInvoiceId = $this->createSale($invoice);
				} elseif($invoice->invoice_type == 'r_sale') {
					$createdInvoiceId = $this->createReturnSale($invoice);
				}
				
				if($createdInvoiceId > 0) {
					dispatch(new UpdateInvoiceCreatedAtJob($createdInvoiceId, $invoice->created_at));
					
					$this->dbConnection->table('invoices')->where('id', $invoice->id)->update(
						[
							'new_db_id' => $createdInvoiceId,
						]
					);
					
				} else {
					Storage::append("outputs/file.txt", $invoice->id . ',');
				}
				
				echo 'created invoice id ' . $createdInvoiceId . "\n";
				echo "ending ............... \n";
				
				$totalDebitAmount = Account::sum('total_debit_amount');
				$totalCreditAmount = Account::sum('total_credit_amount');
				$variation = abs($totalDebitAmount - $totalCreditAmount);
				$this->assertLessThanOrEqual(1, $variation);
			}
			
		}
		
		public function createBeginningInventory($invoice)
		{
			
			$dbItems = $this->dbConnection->table('invoice_items')->where(
				[
					['invoice_id', $invoice->id],
				]
			)->get();
			
			$items = [];
			foreach($dbItems as $item) {
				$requestItem = [];
				$requestItem['id'] = $item->item_id;
				$requestItem['purchase_price'] = $item->price;
				$requestItem['qty'] = $item->qty;
				$requestItem['discount'] = 0;
				$dbItem = $this->dbConnection->table('items')->find($item->item_id);
				$newDBItem = Item::find($item->item_id);
				
				if($dbItem != null && $newDBItem != null) {
					$this->updateItemDetails($newDBItem, $invoice);
					if($dbItem->is_need_serial) {
						$requestItem['serials'] = $this->dbConnection->table('item_serials')->where(
							[
								['purchase_invoice_id', $invoice->id],
								['item_id', $dbItem->id],
							]
						)->pluck('serial')->toArray();
						$requestItem['qty'] = count($requestItem['serials']);
					}
					
					if($requestItem['qty'] <= 0) {
						continue;
					}
					
					$requestItem['testing_available_qty'] = (int)$newDBItem->available_qty;
					$requestItem['testing_item_cost'] = $newDBItem->cost;
					$requestItem['testing_item_total_stock_amount'] = ($newDBItem->available_qty * $newDBItem->cost);
					$requestItem['testing_subtotal'] = ((float)$requestItem['purchase_price'] * (float)$requestItem['qty']) - (float)$requestItem['discount'];
					$requestItem['testing_total_credit_amount'] = $newDBItem->total_credit_amount;
					$requestItem['testing_total_debit_amount'] = $newDBItem->total_debit_amount;
					
					$items[] = $requestItem;
				}
				
			}
			
			$manager = Manager::find(1);
			
			if($items != null) {
				$response = $this->actingAs($manager)->postJson(
					'/api/inventory/beginning', [
						'items' => $items,
					]
				);
				$response
					->dump();
				
				if($response->status() == 200) {
					return json_decode($response->content(), true)['id'];
					
				} else {
					$this->needManualRefactoringInvoices[] = $invoice->id;
				}
				
				$this->restUpdatedItemsDetails();
				
				return json_decode($response->content(), true)['id'];
				
			}
			
			$this->restUpdatedItemsDetails();
			
			return 0;
			
		}
		
		public function createPurchase($invoice)
		{
			
			$purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
			$dbItems = $this->dbConnection->table('invoice_items')->where(
				[
					['invoice_id', $invoice->id],
				]
			)->get();
			
			if($purchase != null && $dbItems != null) {
				$items = [];
				foreach($dbItems as $item) {
					$dbItem = $this->dbConnection->table('items')->find($item->item_id);
					$newDBItem = Item::find($item->item_id);
					
					if($dbItem != null && $newDBItem != null) {
						
						$this->updateItemDetails($newDBItem, $invoice);
						
						if($newDBItem->last_p_price == 0) {
							$price = $item->price;
						} else {
							$maxExpectedPrice = ($newDBItem->last_p_price * 20) / 100 + $newDBItem->last_p_price;
							if($item->price > $maxExpectedPrice) {
								$price = $newDBItem->last_p_price;
							} else {
								$price = $item->price;
								
							}
						}
						$requestItem = [];
						$requestItem['id'] = $item->item_id;
						$requestItem['purchase_price'] = $price;
						$requestItem['price'] = $dbItem->price;
						$requestItem['qty'] = $item->qty;
						$requestItem['discount'] = $item->discount;
						if($dbItem->is_need_serial) {
							$serials = $this->dbConnection->table('item_serials')->where(
								[
									['purchase_invoice_id', $invoice->id],
									['item_id', $dbItem->id],
								]
							)->pluck('serial')->toArray();
							
							$requestSerials = [];
							foreach($serials as $serial) {
								$dbSerial = ItemSerials::where(
									[
										['serial', $serial],
										['item_id', $dbItem->id],
									]
								)->whereIn('status', ['in_stock', 'return_sale'])->first();
								if($dbSerial == null) {
									$requestSerials[] = $serial;
									
								}
							}
							
							$requestItem['serials'] = $requestSerials;
							$requestItem['qty'] = count($requestItem['serials']);
						}
						
						if($requestItem['qty'] <= 0) {
							continue;
						}
						
						$requestItem['testing_available_qty'] = (int)$newDBItem->available_qty;
						$requestItem['testing_item_cost'] = $newDBItem->cost;
						$requestItem['testing_item_total_stock_amount'] = ($newDBItem->available_qty * $newDBItem->cost);
						$requestItem['testing_subtotal'] = ((float)$requestItem['purchase_price'] * (float)$requestItem['qty']) - (float)$requestItem['discount'];
						$requestItem['testing_total_credit_amount'] = $newDBItem->total_credit_amount;
						$requestItem['testing_total_debit_amount'] = $newDBItem->total_debit_amount;
						
						$items[] = $requestItem;
					}
					
				}
				
				$manager = Manager::find(1);
				
				if($items != null) {
					$response = $this->actingAs($manager)->postJson(
						'/api/purchases', [
							'items' => $items,
							'vendor_id' => $purchase->vendor_id,
							'receiver_id' => $purchase->receiver_id,
							'vendor_invoice_id' => $purchase->vendor_inc_number,
							'methods' => $this->getMethods($invoice->id),
						]
					);
					$response
						->dump();
					
					if($response->status() == 200) {
						return json_decode($response->content(), true)['id'];
						
					} else {
						$this->needManualRefactoringInvoices[] = $invoice->id;
					}
				}
				
			}
			
			$this->restUpdatedItemsDetails();
			
			return 0;
		}
		
		public function getMethods($invoiceId)
		{
			$dbMethods = $this->dbConnection->table('payments')->where('invoice_id', $invoiceId)->get();
			
			$methods = [];
			foreach($dbMethods as $method) {
				$methods[] = [
					'id' => $method->paymentable_id,
					'amount' => $method->amount,
				];
			}
			
			return $methods;
		}
		
		public function createReturnPurchase($invoice)
		{
			$parentInvoice = $this->dbConnection->table('invoices')->where('id', $invoice->parent_invoice_id)->first();
			
			if($parentInvoice) {
				$newInvoice = Invoice::find($parentInvoice->new_db_id);
			} else {
				$newInvoice = null;
			}

			dd($parentInvoice);
			
			if($newInvoice) {
				$purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
				$dbItems = $this->dbConnection->table('invoice_items')->where(
					[
						['invoice_id', $invoice->id],
					]
				)->get();
				
				if($purchase != null && $dbItems != null) {
					$items = [];
					foreach($dbItems as $item) {
						
						$dbItem = $this->dbConnection->table('items')->find($item->item_id);
						$newDBItem = Item::find($item->item_id);
						if($dbItem != null && $newDBItem != null && (!$newDBItem->is_service || $newDBItem->available_qty >= $item->qty)) {
							$this->updateItemDetails($newDBItem, $invoice);
							
							
							$itemDBInstance = $newInvoice->items()->where('item_id', $item->item_id)->first();
							
							if($itemDBInstance != null) {
								$requestItem = [];
								$requestItem['id'] = $itemDBInstance->id;
								
								$requestItem['returned_qty'] = $item->qty;
								if($dbItem->is_need_serial) {
									$serials = $this->dbConnection->table('item_serials')->where(
										[
											['r_purchase_invoice_id', $invoice->id],
											['item_id', $dbItem->id],
										]
									)->pluck('serial')->toArray();
									
									$requestSerials = [];
									foreach($serials as $serial) {
										$dbSerial = ItemSerials::where(
											[
												['serial', $serial],
												['item_id', $dbItem->id],
											]
										)->whereIn('status', ['return_purchase'])->first();
										if($dbSerial == null) {
											$requestSerials[] = $serial;
											
										}
									}
									
									$requestItem['serials'] = $requestSerials;
									$requestItem['returned_qty'] = count($requestItem['serials']);
								}
								
								if($requestItem['returned_qty'] <= 0) {
									continue;
								}
								
								$items[] = $requestItem;
							}
							
						}
						
					}
					
					$manager = Manager::find(1);
					
					if($items != null) {
						$response = $this->actingAs($manager)->patchJson(
							"/api/purchases/{$newInvoice->id}", [
								'items' => $items,
								'methods' => $this->getMethods($invoice->id),
							]
						);
						$response
							->dump();
						
						if($response->status() == 200) {
							$response->assertOk();
							$this->restUpdatedItemsDetails();
							
							return json_decode($response->content(), true)['id'];
							
						} else {
							$this->needManualRefactoringInvoices[] = $invoice->id;
						}
						
					}
					
				}
				
				$this->restUpdatedItemsDetails();
			}
			
			return 0;
		}
		
		public function createSale($invoice)
		{
			$sale = $this->dbConnection->table('sale_invoices')->where('invoice_id', $invoice->id)->first();
			$dbItems = $this->dbConnection->table('invoice_items')->where(
				[
					['invoice_id', $invoice->id],
					['belong_to_kit', false],
				]
			)->get();
			
			$payments = $this->getMethods($invoice->id);
			
			if($payments == null && $sale->client_id == 2) {
				$tempResellerAccount = Account::where('slug', 'temp_reseller_account')->first()->toArray();
				$tempResellerAccount['amount'] = $invoice->net;
				$payments[] = $tempResellerAccount;
			}
			
			if($sale != null && $dbItems != null) {
				$items = [];
				
				foreach($dbItems as $item) {
					
					$dbItem = $this->dbConnection->table('items')->find($item->item_id);
					
					$newDBItem = Item::find($item->item_id);
					if($dbItem != null && $newDBItem != null && (!$newDBItem->is_kit || !$newDBItem->is_service || !$newDBItem->is_expense || $newDBItem->available_qty >= $item->qty)) {
						
						$this->updateItemDetails($newDBItem, $invoice);
						
						$requestItem = [];
						$requestItem['id'] = $item->item_id;
						$requestItem['price'] = $item->price;
						$requestItem['qty'] = $item->qty;
						$requestItem['discount'] = $item->discount;
						
						if($dbItem->is_need_serial) {
							$serials = $this->dbConnection->table('item_serials')->where(
								[
									['sale_invoice_id', $invoice->id],
									['item_id', $dbItem->id],
								]
							)->pluck('serial')->toArray();
							$requestSerials = [];
							foreach($serials as $serial) {
								$dbSerial = ItemSerials::where(
									[
										['serial', $serial],
										['item_id', $dbItem->id],
									]
								)->whereIn('status', ['sold'])->first();
								if($dbSerial == null) {
									$requestSerials[] = $serial;
									
								}
							}
							
							$requestItem['serials'] = $requestSerials;
							$requestItem['qty'] = count($requestSerials);
						}
						
						if($dbItem->is_kit) {
							
							$dbKitItems = $this->dbConnection->table('invoice_items')->where(
								[
									['invoice_id', $invoice->id],
									['belong_to_kit', true],
									['parent_kit_id', $item->id],
								]
							)->get();
							$requestItem['items'] = [];
							
							// dd($dbKitItems );
							
							foreach($dbKitItems as $kitItem) {
								
								$dbKitItem = $this->dbConnection->table('items')->find($kitItem->item_id);
								$this->dbConnection->table('items')->find($kitItem->item_id);
								$kitItem = KitItems::where(
									[
										['item_id', $dbKitItem->id],
										['kit_id', $item->item_id],
									]
								)->first();
								$newDBInstance = Item::find($dbKitItem->id);
								
								// dd($kitItem);
								
								if($kitItem != null && $newDBInstance != null && (!$newDBInstance->is_service || !$newDBInstance->is_expense || $newDBInstance->available_qty >= $kitItem->qty)) {
									
									$this->updateItemDetails($newDBInstance, $invoice);
									
									$requestKitItem['qty'] = $kitItem->qty;
									$requestKitItem['id'] = $newDBInstance->id;
									
									$items = [];
									if($dbKitItem->is_need_serial) {
										$requestKitItem['serials'] = $this->dbConnection->table('item_serials')->where(
											[
												['sale_invoice_id', $invoice->id],
												['item_id', $dbKitItem->id],
											]
										)->pluck('serial')->toArray();
										
										// dd($requestKitItem);
										$requestKitItem['qty'] = count($requestKitItem['serials']);
									}
									
									$requestItem['items'][] = $requestKitItem;
									
								}
								
							}
							// dd($requestItem);
							
							if($requestItem['items'] == null) {
								continue;
							}
							
							// dd($requestItem);
						}
						
						if($requestItem['qty'] <= 0) {
							continue;
						}
						
						$items[] = $requestItem;
						
					}
					
				}
				$manager = Manager::find(1);
				
				if($items != null) {
					$response = $this->actingAs($manager)->postJson(
						'/api/sales', [
							'items' => $items,
							'client_id' => $sale->client_id,
							'salesman_id' => $sale->salesman_id,
							'methods' => $payments,
							'without_creating_expenses_purchases' => true,
						]
					);
					$response
						->dump();
					
					if($response->status() == 201) {
						$response->assertCreated();
						return json_decode($response->content(), true)['id'];
						
					}
					$this->restUpdatedItemsDetails();
					
				}
				
			}
			
			$this->restUpdatedItemsDetails();
			
			return 0;
		}
		
		public function createReturnSale($invoice)
		{
			
			$parentInvoice = $this->dbConnection->table('invoices')->where('id', $invoice->parent_invoice_id)->first();
			
			if($parentInvoice) {
				$newInvoice = Invoice::find($parentInvoice->new_db_id);
			} else {
				$newInvoice = null;
			}
			
			if($newInvoice != null) {
				$sale = $this->dbConnection->table('sale_invoices')->where('invoice_id', $invoice->id)->first();
				$dbItems = $this->dbConnection->table('invoice_items')->where(
					[
						['invoice_id', $invoice->id],
						['belong_to_kit', false],
					]
				)->get();
				
				$payments = $this->getMethods($invoice->id);
				if($payments == null && $sale->client_id == 2) {
					$tempResellerAccount = Account::where('slug', 'temp_reseller_account')->first()->toArray();
					$tempResellerAccount['amount'] = $invoice->net;
					$payments[] = $tempResellerAccount;
				}
				
				if($sale != null && $dbItems != null) {
					$items = [];
					
					foreach($dbItems as $key => $item) {
						
						$dbItem = $this->dbConnection->table('items')->find($item->item_id);
						
						$newDBItem = Item::find($item->item_id);
						
						if($newDBItem != null) {
							$purchaseInvoiceItem = $newInvoice->items()->where('item_id', $newDBItem->id)->first();
							
						} else {
							$purchaseInvoiceItem = null;
						}
						
						if($dbItem != null && $newDBItem != null && $purchaseInvoiceItem != null) {
							
							$this->updateItemDetails($newDBItem, $invoice);
							
							$requestItem = [];
							$requestItem['id'] = $purchaseInvoiceItem->id;
							$requestItem['returned_qty'] = $item->qty;
							
							if($dbItem->is_need_serial) {
								$serials = $this->dbConnection->table('item_serials')->where(
									[
										['sale_invoice_id', $invoice->id],
										['item_id', $dbItem->id],
									]
								)->pluck('serial')->toArray();
								$requestSerials = [];
								foreach($serials as $serial) {
									$dbSerial = ItemSerials::where(
										[
											['serial', $serial],
											['item_id', $dbItem->id],
										]
									)->whereIn('status', ['return_sale'])->first();
									if($dbSerial == null) {
										$requestSerials[] = $serial;
										
									}
								}
								
								$requestItem['serials'] = $requestSerials;
								$requestItem['returned_qty'] = count($requestItem['serials']);
							}
							
							if($dbItem->is_kit) {
								
								$dbKitItems = $this->dbConnection->table('invoice_items')->where(
									[
										['invoice_id', $invoice->id],
										['belong_to_kit', true],
										['parent_kit_id', $item->item_id],
									]
								)->get();
								$requestItem['items'] = [];
								
								foreach($dbKitItems as $kitItem) {
									
									$dbKitItem = $this->dbConnection->table('items')->find($kitItem->item_id);
									$kitItem = KitItems::where(
										[
											['item_id', $dbKitItem->id],
											['kit_id', $item->id],
										]
									)->first();
									$newDBInstance = Item::find($dbKitItem->id);
									
									$invoiceKitPurchaseItem = $newInvoice->items()->where(
										[
											['belong_to_kit', true],
											['item_id', $newDBInstance->id],
										]
									)->first();
									if($kitItem != null && $newDBInstance != null && $invoiceKitPurchaseItem != null) {
										
										$this->updateItemDetails($newDBInstance, $invoice);
										
										$requestKitItem['returned_qty'] = $kitItem->qty;
										$requestKitItem['id'] = $invoiceKitPurchaseItem->id;
										if($dbKitItem->is_need_serial) {
											$requestKitItem['serials'] = $this->dbConnection->table('item_serials')->where(
												[
													['sale_invoice_id', $invoice->id],
													['item_id', $dbKitItem->id],
												]
											)->pluck('serial')->toArray();
											$requestKitItem['returned_qty'] = count($requestKitItem['serials']);
										}
										$requestItem['items'][] = $requestKitItem;
									}
									
								}
								if($requestItem['items'] == null) {
									continue;
								}
							}
							
							if($requestItem['returned_qty'] <= 0) {
								continue;
							}
							
							$items[] = $requestItem;
							
						}
						
					}
					
					$manager = Manager::find(1);
					
					if($items != null) {
						$response = $this->actingAs($manager)->patchJson(
							"/api/sales/{$newInvoice->id}", [
								'items' => $items,
								'methods' => $payments,
							]
						);
						$response
							->dump()
							->assertCreated();
						
						if($response->status() == 201) {
							$response->assertCreated();
							$this->restUpdatedItemsDetails();
							
							return json_decode($response->content(), true)['id'];
							
						} else {
							$this->needManualRefactoringInvoices[] = $invoice->id;
						}
						
					}
					
				}
			}
			
			$this->restUpdatedItemsDetails();
			
			return 0;
			
		}
		
		public function test_create_purchases()
		{
			
			
			$ids = [
//				4 => 1092,
//				5 => 1108,
//				6 => 1142,
//				7 => 1328,
//				8 => 1374,
//				9 => 1470,
//				11 => 1557,
//				12 => 2594,
//				13 => 3053,
				14 => 3240,
//				15 => 3371,
//				16 => 3537,
//				17 => 3738,
//				18 => 3759,
//				19 => 3913,
//				20 => 3957,
//				21 => 4095,
//				22 => 4207,
//				23 => 4223,
//				24 => 6158,
//				25 => 8914,
//				26 => 9365,
//				27 => 9429,
//				28 => 9588,
//				29 => 9589,
//				30 => 9840,
//				31 => 9863,
//				32 => 9969,
//				33 => 9970,
//				34 => 11914,
//				35 => 14333,
//				36 => 15754,
//				37 => 16913,
//				38 => 16927,
//				39 => 16987,
//				40 => 17729,
//				41 => 22207,
			];
			$invoices = $this->dbConnection->table('invoices')->whereIn('id', $ids)->get();
			foreach($invoices as $invoice) {
				echo "\nstarting.......................\n";
				echo "source id " . $invoice->id . "\n";
				echo 'source type ' . $invoice->invoice_type . "\n";
				$createdInvoiceId = 0;
//
//				dd($invoice);
//				if($invoice->invoice_type == 'beginning_inventory') {
//					$createdInvoiceId = $this->createBeginningInventory($invoice);
//				} else
				if($invoice->invoice_type == 'purchase') {
					$createdInvoiceId = $this->createPurchase($invoice);
				} elseif($invoice->invoice_type == 'r_purchase') {
					$createdInvoiceId = $this->createReturnPurchase($invoice);
				}

//					elseif($invoice->invoice_type == 'sale') {
//					$createdInvoiceId = $this->createSale($invoice);
//				} elseif($invoice->invoice_type == 'r_sale') {
//					$createdInvoiceId = $this->createReturnSale($invoice);
//				}
				
				if($createdInvoiceId > 0) {
					dispatch(new UpdateInvoiceCreatedAtJob($createdInvoiceId, $invoice->created_at));
					
					$this->dbConnection->table('invoices')->where('id', $invoice->id)->update(
						[
							'new_db_id' => $createdInvoiceId,
						]
					);
					
				} else {
					Storage::append("outputs/purchase_issues_invoices.txt", $invoice->id . ',');
				}
				
				echo 'created invoice id ' . $createdInvoiceId . "\n";
				echo "ending ............... \n";
				
				$totalDebitAmount = Account::sum('total_debit_amount');
				$totalCreditAmount = Account::sum('total_credit_amount');
				$variation = abs($totalDebitAmount - $totalCreditAmount);
//				$this->assertLessThanOrEqual(1, $variation);
			}
			
		}
		
		public function create_sales()
		{
			$ids = config('global.issued_invoices');
			
			$invoices = $this->dbConnection->table('invoices')->whereIn('id', $ids)->where([['invoice_type', 'sale'], ['id', '!=', 1613]])->get();
			
			foreach($invoices as $invoice) {
				echo "\nstarting.......................\n";
				echo "source id " . $invoice->id . "\n";
				echo 'source type ' . $invoice->invoice_type . "\n";
				$createdInvoiceId = 0;
				
				if($invoice->invoice_type == 'beginning_inventory') {
					$createdInvoiceId = $this->createBeginningInventory($invoice);
				} elseif($invoice->invoice_type == 'purchase') {
					$createdInvoiceId = $this->createPurchase($invoice);
				} elseif($invoice->invoice_type == 'r_purchase') {
					$createdInvoiceId = $this->createReturnPurchase($invoice);
				} elseif($invoice->invoice_type == 'sale') {
					$createdInvoiceId = $this->createSale($invoice);
				} elseif($invoice->invoice_type == 'r_sale') {
					$createdInvoiceId = $this->createReturnSale($invoice);
				}
				
				if($createdInvoiceId > 0) {
					dispatch(new UpdateInvoiceCreatedAtJob($createdInvoiceId, $invoice->created_at));
					
					$this->dbConnection->table('invoices')->where('id', $invoice->id)->update(
						[
							'new_db_id' => $createdInvoiceId,
						]
					);
					
				} else {
					Storage::append("outputs/sales_issues_invoices.txt", $invoice->id . ',');
				}
				
				echo 'created invoice id ' . $createdInvoiceId . "\n";
				echo "ending ............... \n";
				
				$totalDebitAmount = Account::sum('total_debit_amount');
				$totalCreditAmount = Account::sum('total_credit_amount');
				$variation = abs($totalDebitAmount - $totalCreditAmount);
				$this->assertLessThanOrEqual(1, $variation);
			}
			
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->dbConnection = DB::connection('data_source');
			
		}
	}
