<?php
	
	namespace App\Http\Requests\Sales;
	
	use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
	use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
	use App\Jobs\Sales\Accounting\StoreReturnSaleTransactionsJob;
	use App\Jobs\Sales\Items\StoreReturnSaleItemsJob;
	use App\Jobs\Sales\Payment\StoreReturnSalePaymentsJob;
	use App\Models\Invoice;
	use App\Models\InvoiceItems;
	use App\Models\Sale;
	use Exception;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\ValidationException;
	
	class StoreReturnSaleRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'items' => 'required|array',
				'items.*.id' => 'integer|required|exists:invoice_items,id',
				'items.*.returned_qty' => 'required',
				'items.*.serials' => 'nullable|array',
//            'items.*.serials.*' => 'required|array',
//				'items.*.serials.*' => 'required|exists:item_serials,serial',
				"methods" => 'nullable|array',
				'methods.*.id' => 'integer|required|exists:accounts,id',
			];
		}
		
		public function store(Invoice $saleInvoice)
		{
			DB::beginTransaction();
			try {
				// return $this->all();
//            dd($this->all());
				$this->validateInvoiceType($saleInvoice);
				$returnedItems = $this->getReturnedItems();
				$this->validateItems($returnedItems, $saleInvoice);
				$authUser = auth()->user();
				$invoice = Invoice::create(
					[
						'invoice_type' => 'return_sale',
						'notes' => $this->has('notes') ? $this->input('notes') : "",
						'creator_id' => $authUser->id,
						'organization_id' => $saleInvoice->organization_id,
						'branch_id' => $saleInvoice->branch_id,
						'department_id' => $saleInvoice->department_id,
						'parent_id' => $saleInvoice->id,
						'user_id' => $saleInvoice->user_id,
						'managed_by_id' => $saleInvoice->managed_by_id,
					]
				);
				$returnSaleInvoice = $invoice->sale()->create(
					[
						'salesman_id' => $saleInvoice->sale->salesman_id,
						'client_id' => $saleInvoice->sale->client_id,
						'organization_id' => $saleInvoice->organization_id,
						'invoice_type' => 'return_sale',
						'alice_name' => $saleInvoice->sale->alice_name,
						"prefix" => "RSI-",
					]
				);
				dispatch(new UpdateInvoiceNumberJob($invoice, 'RSI-'));
				dispatch(new StoreReturnSaleItemsJob($invoice, $saleInvoice, $returnedItems));
				dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
				
				/**
				 *
				 * ========================================================
				 * validate payments amount should be after updating invoice totals
				 * ========================================================
				 *
				 */
				$paymentsMethods = $this->validatePaymentsAndGetPaymentMethods($invoice->fresh());
				dispatch(new StoreReturnSalePaymentsJob($invoice, $paymentsMethods));
				dispatch(new StoreReturnSaleTransactionsJob($invoice));
				
				DB::commit();
				return $invoice;
			} catch(QueryException $queryException) {
				DB::rollBack();
				throw $queryException;
			} catch(ValidationException $validationException) {
				DB::rollBack();
				throw $validationException;
			} catch(Exception $exception) {
				DB::rollBack();
				throw $exception;
				
			}
		}
		
		private function validateInvoiceType(Invoice $invoice)
		{
			if($invoice->sale == null || !$invoice->sale instanceof Sale || $invoice->invoice_type != 'sale' || $invoice->sale->invoice_type != 'sale') {
				$error = ValidationException::withMessages(
					[
						"invoice" => ['must be sales invoice'],
					]
				);
				throw $error;
			}
			
		}
		
		private function getReturnedItems()
		{
			$items = [];
			foreach($this->input('items') as $item) {
				if((int)$item['returned_qty'] >= 1) {
					$items[] = $item;
				}
			}
			
			if(empty($items)) {
				$error = ValidationException::withMessages(
					[
						"invoice" => ['returned items must be at lest one item'],
					]
				);
				throw $error;
			}
			return $items;
		}
		
		private function validateItems(array $returnedItems, Invoice $invoice)
		{
			
			foreach($returnedItems as $item) {
				
				$dbInvoiceItem = InvoiceItems::find($item['id']);
				$returnedQty = (int)$item['returned_qty'];
				$item = collect($item);
				$this->validateBelongToInvoice($invoice, $dbInvoiceItem);
				if($dbInvoiceItem->item->is_kit) {
					$this->validateKit($dbInvoiceItem, $item, $returnedQty);
				} else {
					$this->validateItemsQty($dbInvoiceItem, $item);
					if($dbInvoiceItem->item->is_need_serial) {
						$this->validateItemsSerials($dbInvoiceItem, $item, $returnedQty);
					}
				}
				
			}
			
		}
		
		private function validateBelongToInvoice(Invoice $sale, InvoiceItems $invoiceItem)
		{
			if($invoiceItem->invoice_id != $sale->id) {
				$error = ValidationException::withMessages(
					[
						"invoice" => ['all items must belongs to current invoice'],
					]
				);
				throw $error;
			}
			
		}
		
		private function validateKit(InvoiceItems $invoiceItem, $requestItem, $returnedQty)
		{
			
			// die($requestItem);
			$requestKitChildren = collect($requestItem->get('items'));
			
			$kitItems = $invoiceItem->invoice->items()->where(
				[
					['belong_to_kit', true],
					['parent_kit_id', $invoiceItem->id],
				]
			)->get();
			
			
			foreach($kitItems as $kitItem) {
				$kitItemRequestData = collect($requestKitChildren->where('id', $kitItem->item_id)->first());
				
				
				$qtyPerItem = $kitItem->qty / $invoiceItem->qty;
				$KitItemReturnedQty = $returnedQty * $qtyPerItem;
				$requestItem['returned_qty'] = $KitItemReturnedQty;
				$requestItem['id'] = $kitItem->id;
				$this->validateItemsQty($kitItem, $requestItem);
				
				if($kitItem->item->is_need_serial) {
					$requestItem['serials'] = $kitItemRequestData->get('serials');
					
					// die($requestItem);
					
					$this->validateItemsSerials($kitItem, $kitItemRequestData, $KitItemReturnedQty);
				}
				
			}
		}
		
		private function validateItemsQty(InvoiceItems $invoiceItem, $requestItem)
		{
			$returnedQty = $invoiceItem->returned_qty + $requestItem->get('returned_qty');
			
			if($returnedQty < 0) {
				$error = ValidationException::withMessages(
					[
						"items" => ['item qty should be greater than returned qty'],
					]
				);
				throw $error;
			}
		}
		
		private function validateItemsSerials(InvoiceItems $invoiceItem, $requestItem, $returnedQty)
		{
			
			
			$serials = (array)$requestItem->get('serials');
			
			if($serials == null) {
				$error = ValidationException::withMessages(
					[
						"items" => ['serials should not be null for this item '],
					]
				);
				
				throw $error;
				
			}
			
			if(count($serials) != $returnedQty) {
				$error = ValidationException::withMessages(
					[
						"items" => ['serials count should match item returned '],
					]
				);
				
				throw $error;
			}
			
			foreach($serials as $serial) {
				$dbSerial = $invoiceItem->item->serials()->where(
					[
						['sale_id', $invoiceItem->invoice_id],
						['status', 'sold'],
						['serial', $serial],
					]
				)->first();
				
				if($dbSerial == null) {
					$error = ValidationException::withMessages(
						[
							"items" => ['invalid item serial'],
						]
					);
					
					throw $error;
				}
			}
			
		}
		
		private function validatePaymentsAndGetPaymentMethods(Invoice $invoice)
		{
			$netAmount = (float)$invoice->fresh()->net;
			$methodsCollects = collect($this->input('methods'));
			$paymentsMethodsCount = $methodsCollects->count();
			$totalPaidAmount = (float)$methodsCollects->sum('amount');
			$user = $invoice->sale->client;
			if($user->is_system_user) {
				if($totalPaidAmount !== $netAmount) {
					if($paymentsMethodsCount < 1) {
						throw ValidationException::withMessages(['payments' => "summation of payments methods should match invoice net "]);
					} else {
						$variationAmount = $netAmount - (float)$totalPaidAmount;
						$methods = $this->input('methods');
						if($variationAmount > 0) {
							$methods[0]['amount'] = (float)$methods[0]['amount'] + (float)$variationAmount;
						}
						
						if($variationAmount < 0) {
							$methods[0]['amount'] = (float)$methods[0]['amount'] - (float)abs($variationAmount);
						}
						return $methods;
					}
				}
				
			}
			return $this->input('methods');
		}
	}
