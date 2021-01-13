<?php
	
	namespace App\Jobs\Items\AvailableQty;
	
	use App\Models\InvoiceItems;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class UpdateAvailableQtyByInvoiceItemJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $invoiceItem;
		/**
		 * @var bool
		 */
		private $reverse;
		
		/**
		 * Create a new job instance.
		 *
		 * @param InvoiceItems $invoiceItem
		 * @param bool $reverse
		 */
		public function __construct(InvoiceItems $invoiceItem, $reverse = false)
		{
			$this->invoiceItem = $invoiceItem;
			$this->reverse = $reverse;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			
			$availableQty = $this->invoiceItem->item->available_qty;
			if(in_array($this->invoiceItem->invoice_type, ['purchase', 'return_sale', 'beginning_inventory','inventory_adjustment']) && !$this->reverse) {
				$availableQtyAfterInvoiceItem = (int)$availableQty + (int)$this->invoiceItem->qty;
			} else {
				$availableQtyAfterInvoiceItem = (int)$availableQty - (int)$this->invoiceItem->qty;
			}


//        dd($availableQty,$availableQtyAfterInvoiceItem);
			$this->invoiceItem->item()->update(
				[
					'available_qty' => $availableQtyAfterInvoiceItem
				]
			);
		}
	}
