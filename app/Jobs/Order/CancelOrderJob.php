<?php
	
	namespace App\Jobs\Order;
	
	use AliAbdalla\Whatsapp\Whatsapp;
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Models\Order;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class CancelOrderJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		/**
		 * @var Order
		 */
		private $order;
		private $isManual;
		
		/**
		 * Create a new job instance.
		 *
		 * @param Order $order
		 * @param $isManual
		 */
		public function __construct(Order $order,$isManual = false)
		{
			//
			$this->order = $order;
			$this->isManual = $isManual;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			$isManual = $this->isManual;
			$order = $this->order;
			$messageTemplate = view('whatsapp.order_canceled', compact('order','isManual'))->toHtml();
			
			Whatsapp::sendMessage($messageTemplate, ['966504956211']);
			$this->order->update(
				[
					'status' => 'canceled'
				]
			);
			
			$this->cancelHoldQtyForItems($this->order);
			
		}
		
		
		private function cancelHoldQtyForItems(Order $order)
		{
			foreach($order->itemsQtyHolders as $holdQty) {
				UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
				$holdQty->update(
					[
						'status' => 'destroyed'
					]
				);
			}
			
		}
	}
