<?php
	
	namespace App\Jobs\Purchases\Items;
	
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
	use App\Jobs\Items\Price\UpdateItemLastPurchasePriceJob;
	use App\Jobs\Items\Price\UpdateItemSalesPriceJob;
	use App\Jobs\Items\Serial\AddItemSerialByInvoiceItemJob;
	use App\Models\Invoice;
	use App\Models\InvoiceItems;
	use App\Models\Item;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class StorePurchaseItemsJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $invoice, $items, $loggedUser;
		/**
		 * @var bool
		 */
		private $isDraft;
		/**
		 * @var string
		 */
		private $invoiceType;
		
		/**
		 * Create a new job instance.
		 *
		 * @param Invoice $invoice
		 * @param $items
		 * @param bool $isDraft
		 * @param string $invoiceType
		 */
		public function __construct(Invoice $invoice, $items, $isDraft = false, $invoiceType = 'purchase')
		{
			$this->items = $items;
			$this->invoice = $invoice;
			$this->loggedUser = auth()->user();
			$this->isDraft = $isDraft;
			$this->invoiceType = $invoiceType;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			foreach((array)$this->items as $key => $value) {
				$item = Item::find($value['id']);
				$requestItemCollection = collect($value);
				/**
				 * ==========================================================
				 * store available qty/cost before create new item
				 * ==========================================================
				 */
				$availableQtyBeforeInvoiceItem = $item->available_qty;
				$costBeforeInvoiceItem = $item->cost;
				
				/**
				 * ==========================================================
				 * create new invoice item instance
				 * ==========================================================
				 */
				$invoiceItem = $this->createInvoiceItem($item, $requestItemCollection);
				
				
				/**
				 * ==========================================================
				 * if it need serial change the serial list status
				 * ==========================================================
				 */
				if($item->is_need_serial) {
					dispatch_now(new AddItemSerialByInvoiceItemJob($requestItemCollection->get('serials'), $invoiceItem, $this->isDraft));
				}
				/**
				 * ==========================================================
				 * change actual item data if it's not draft items
				 * ==========================================================
				 */
				if(!$this->isDraft) {
					/**
					 * ==========================================================
					 * update qty should be before update cost
					 * ==========================================================
					 */
					dispatch_now(new UpdateAvailableQtyByInvoiceItemJob($invoiceItem));
					/**
					 * ==========================================================
					 * we neeed for available qty and cost before new invoice item
					 * ==========================================================
					 */
					dispatch_now(new UpdateItemCostByInvoiceItemJob($invoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));
					
					/**
					 * ==========================================================
					 * update last purchase price for this item
					 * ==========================================================
					 */
					if($this->invoice->invoice_type == 'purchase') {
						dispatch_now(new UpdateItemLastPurchasePriceJob($invoiceItem));
						// To Do => update sales price
						$salesPrice = (float)$requestItemCollection->get('price_with_tax');
						dispatch_now(new UpdateItemSalesPriceJob($invoiceItem, $salesPrice));
					}
					
					
					/**
					 * ==========================================================
					 * set cost and available qty to the created invoice item
					 * ==========================================================
					 */
					$this->setCostAndAvailableQty($invoiceItem);
				}
				
				
			}
		}
		
		private function createInvoiceItem(Item $item, $requestItemCollection)
		{
			
			$purchasePrice = (float)$requestItemCollection->get('purchase_price');
			if($requestItemCollection->has('discount')) {
				$discount = (float)$requestItemCollection->get('discount');//
			} else {
				$discount = 0;
			}
			
			$qty = (int)$requestItemCollection->get('qty');
			$total = $purchasePrice * $qty;
			$subtotal = $total - $discount;
			if($this->invoiceType == 'purchase') {
				$tax = ($subtotal * $item->vtp) / 100;
				
			} else {
				$tax = 0;
				
			}
			$net = $subtotal + $tax;
			$total = $subtotal;
			$data['price'] = $subtotal / $qty;
			$data['invoice_type'] = $this->invoiceType;
			$data['user_id'] = $this->invoice->purchase->vendor_id;
			$data['qty'] = $qty;
			$data['discount'] = 0;//$discount
			$data['total'] = $total;
			$data['subtotal'] = $subtotal;
			$data['tax'] = $tax;
			$data['net'] = $net;
			$data['organization_id'] = $this->loggedUser->organization_id;
			$data['creator_id'] = $this->loggedUser->id;
			$data['item_id'] = $item->id;
			$data['is_draft'] = $this->isDraft;
			return $this->invoice->items()->create($data);
		}
		
		private function setCostAndAvailableQty(InvoiceItems $invoiceItem)
		{
			$invoiceItem->update(
				[
					'cost' => $invoiceItem->item->fresh()->cost,
					'available_qty' => $invoiceItem->item->fresh()->available_qty,
					'total_stock_cost_amount' => (float)$invoiceItem->item->fresh()->cost * (float)$invoiceItem->item->fresh()->available_qty,
				]
			);
		}
	}
