<?php

	namespace App\Jobs\Order;

	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Models\Invoice;
	use App\Models\Order;
	use App\Models\OrderItemQtyHolder;
	use Carbon\Carbon;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;

	class HoldItemQtyJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		/**
		 * @var Invoice
		 */
		private $invoice;
		/**
		 * @var Order
		 */
		private $order;

		/**
		 * Create a new job instance.
		 *
		 * @param Invoice $invoice
		 * @param Order $order
		 */
		public function __construct(Invoice $invoice, Order $order)
		{
			//
			$this->invoice = $invoice;
			$this->order = $order;
		}

		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{

			foreach($this->invoice->items as $item) {
                dispatch_now(new UpdateAvailableQtyByInvoiceItemJob($item));
                $item->orderQtyHolders()->create(
					[
						'order_id' => $this->order->id,
						'qty' => $item->qty,
						'hold_created_at' => Carbon::now(),
						'hold_destroy_at' => Carbon::now()->addMinutes(30),
					]
				);

			}
		}
	}
