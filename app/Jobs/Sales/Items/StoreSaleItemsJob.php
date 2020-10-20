<?php
	
	namespace App\Jobs\Sales\Items;
	
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Jobs\Items\Cost\UpdateItemCostByInvoiceItemJob;
	use App\Jobs\Items\Kit\UpdateKitByInvoiceItemsJob;
	use App\Jobs\Items\Profit\UpdateItemProfitByInvoiceItem;
	use App\Jobs\Items\Serial\UpdateItemSerialStatusByInvoiceItemJob;
	use App\Models\Invoice;
	use App\Models\InvoiceItems;
	use App\Models\Item;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class StoreSaleItemsJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $invoice, $items, $loggedUser;
		/**
		 * @var bool
		 */
		private $isDraft;
		
		/**
		 * Create a new job instance.
		 *
		 * @param Invoice $invoice
		 * @param $items
		 * @param bool $isDraft
		 */
		public function __construct(Invoice $invoice, $items, $isDraft = false)
		{
			$this->items = $items;
			$this->invoice = $invoice;
			$this->loggedUser = auth()->user();
			$this->isDraft = $isDraft;
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
				if($item->is_kit) {
					$this->storeKit($item, $value);
				} else {
					$this->storeItem($item, $value);
				}
			}
		}
		
		public function storeKit(Item $dbItem, $value)
		{
			$itemPureCollection = collect($value);
			$qty = (int)$itemPureCollection->get('qty');
			$data['belong_to_kit'] = false;
			$data['parent_kit_id'] = 0;
			$data['discount'] = (float)($dbItem->data->discount * $qty);
			$data['price'] = (float)$dbItem->data->total;
			$data['qty'] = $qty;
			$data['total'] = (float)$data['price'] * (float)$data['qty'];
			$data['subtotal'] = (float)$data['total'] - (float)$data['discount'];
			$data['net'] = (float)$data['subtotal'] + ($dbItem->data->tax * $qty);
			$data['organization_id'] = $this->invoice->organization_id;
			$data['creator_id'] = $this->invoice->creator_id;
			$data['item_id'] = $dbItem->id;
			$data['user_id'] = $this->invoice->user_id;
			$data['invoice_type'] = 'sale';
			$data['is_kit'] = true;
			$data['is_draft'] = $this->isDraft;
			$invoiceKitItem = $this->invoice->items()->create($data);
			$this->storeKitItems($itemPureCollection, $dbItem, $invoiceKitItem->id, $qty);
			dispatch(new UpdateKitByInvoiceItemsJob($invoiceKitItem));
		}
		
		public function storeKitItems($itemPureCollection, Item $dbKit, $invoiceKitId, $qty)
		{
			foreach($dbKit->items as $kitItem) {
				$dbItem = $kitItem->item;
				if($dbItem->is_need_serial) {
					$data = collect(collect($itemPureCollection->get('items'))->where('id', $kitItem->item_id)->first());
					if(!empty($data)) {
						$sendData['serials'] = $data->get('serials');
					} else {
						$sendData['serials'] = [];
					}
				}
				$sendData['qty'] = (int)$kitItem->qty * (int)$qty;
				$sendData['discount'] = (float)$kitItem->discount * (int)$qty;
				$sendData['price'] = $kitItem->price;
				$sendData['belong_to_kit'] = true;
				$sendData['parent_kit_id'] = $invoiceKitId;
				$sendData['id'] = $dbItem->id;
				
				/***
				 * =====================================
				 * create new items
				 * =====================================
				 */
				$this->storeItem($dbItem, $sendData);
				
			}
		}
		
		private function storeItem(Item $item, $value)
		{
			
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
			 * change actual item data if it's not draft items
			 * ==========================================================
			 */
			if(!$this->isDraft) {
				
				
				/**
				 * ==========================================================
				 * if it need serial change the serial list status
				 * ==========================================================
				 */
				if($item->is_need_serial) {
					dispatch(new UpdateItemSerialStatusByInvoiceItemJob($requestItemCollection->get('serials'), $invoiceItem, $this->isDraft));
				}
				
				
				if(!$item->is_service) {
					/**
					 * ==========================================================
					 * update qty should be before update cost
					 * ==========================================================
					 */
					dispatch(new UpdateAvailableQtyByInvoiceItemJob($invoiceItem));
					/**
					 * ==========================================================
					 * we neeed for available qty and cost before new invoice item
					 * ==========================================================
					 */
					dispatch(new UpdateItemCostByInvoiceItemJob($invoiceItem, $availableQtyBeforeInvoiceItem, $costBeforeInvoiceItem));
					
					/**
					 * ==========================================================
					 * set cost and available qty to the created invoice item
					 * ==========================================================
					 */
					$this->setCostAndAvailableQty($invoiceItem);
					
				}
				
				/**
				 * ==========================================================
				 * update items total profits amount
				 * ==========================================================
				 */
				dispatch(new UpdateItemProfitByInvoiceItem($invoiceItem));
				
			}
			
		}
		
		private function createInvoiceItem(Item $item, $requestItemCollection)
		{
			
			$isBelongToKit = (bool)$requestItemCollection->get('belong_to_kit');
			$parentKitId = (int)$requestItemCollection->get('parent_kit_id');
			if(!$isBelongToKit && $item->is_fixed_price) {
				$price = (float)$item->price;
			} else {
				$price = (float)$requestItemCollection->get('price');
			}
			
			$discount = (float)$requestItemCollection->get('discount');
			$qty = (int)$requestItemCollection->get('qty'); // 10
			$total = $price * $qty;
			$subtotal = $total - $discount;
			$tax = ($subtotal * $item->vts) / 100;
			$net = $subtotal + $tax;
			
			$data['belong_to_kit'] = $isBelongToKit;
			$data['parent_kit_id'] = $parentKitId;
			$data['invoice_type'] = 'sale';
			$data['user_id'] = $this->invoice->user_id;
			$data['qty'] = $qty;
			$data['price'] = $price;
			$data['discount'] = $discount;
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
