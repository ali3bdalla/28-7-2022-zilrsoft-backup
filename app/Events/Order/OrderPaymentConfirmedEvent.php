<?php
	
	namespace App\Events\Order;
	
	use App\Models\Order;
	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Broadcasting\PresenceChannel;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	
	class OrderPaymentConfirmedEvent
	{
		use Dispatchable, InteractsWithSockets, SerializesModels;
		
		/**
		 * @var Order
		 */
		private $order;
		
		/**
		 * Create a new event instance.
		 *
		 * @param Order $order
		 */
		public function __construct(Order $order)
		{
			//
			$this->order = $order;
		}
		
		public function broadcastAs()
		{
			return 'order-payment-confirmed';
		}
		
		/**
		 * Get the channels the event should broadcast on.
		 *
		 * @return Channel|array
		 */
		public function broadcastOn()
		{
			return new PrivateChannel('order-payment-confirmed');
		}
	}
