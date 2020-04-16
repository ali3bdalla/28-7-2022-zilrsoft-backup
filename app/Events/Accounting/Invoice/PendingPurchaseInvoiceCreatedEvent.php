<?php
	
	namespace App\Events\Accounting\Invoice;
	
	use App\Invoice;
	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Broadcasting\PresenceChannel;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	
	class PendingPurchaseInvoiceCreatedEvent implements ShouldBroadcast
	{
		use Dispatchable,InteractsWithSockets,SerializesModels;
		/**
		 * @var Invoice
		 */
		public $message;
		
		/**
		 * Create a new event instance.
		 *
		 * @param $message
		 */
		public function __construct( $message)
		{
			$this->message = $message;
		}
		
//		public function broadcastOn()
//		{
//			return new Channel("broadcastingChannel");
//		}
		
		public function broadcastOn()
		{
			return ['my-channel'];
		}
		
		public function broadcastAs()
		{
			return 'my-event';
		}
		
		
//
//		/**
//		 * Get the channels the event should broadcast on.
//		 *
//		 * @return Channel|array
//		 */
//		public function broadcastOn()
//		{//pending-purchase-invoice-broadcasting-channel-' . $this->invoice->id . '-' .$this->invoice->organization_id
//			return new Channel('pending');
//		}
//
//		public function broadcastAs()
//		{
//			return 'my-event';
//		}
		
	
	}
