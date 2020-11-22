<?php
	
	namespace App\Jobs\Sales\Order;
	
	use App\Models\Invoice;
	use App\Models\Order;
	use Carbon\Carbon;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class CreateSalesOrderJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $orderAutoCancelAfter = 5;
		/**
		 * @var Invoice
		 */
		private $invoice;
		private $shippingMethodId;
		private $shippingAddressId;
		
		/**
		 * Create a new job instance.
		 *
		 * @param Invoice $invoice
		 * @param $shippingMethodId
		 * @param $shippingAddressId
		 */
		public function __construct(Invoice $invoice, $shippingMethodId, $shippingAddressId)
		{
			//
			$this->invoice = $invoice;
			$this->shippingMethodId = $shippingMethodId;
			$this->shippingAddressId = $shippingAddressId;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			$order = new Order();
			$order->user_id = $this->invoice->user_id;
			$order->shipping_method_id = $this->shippingMethodId;
			$order->shipping_address_id = $this->shippingAddressId;
			$order->draft_id = $this->invoice->id;
			$order->net = $this->invoice->net;
			$order->auto_cancel_at = Carbon::now()->addMinutes($this->orderAutoCancelAfter);
			$order->is_should_pay_notified = false;
			$order->should_pay_last_notification_at = Carbon::now()->addMinutes($this->orderAutoCancelAfter - 3);
			$order->status = 'issued';
			$order->save();
			return $order->fresh();
			//
		}
	}
