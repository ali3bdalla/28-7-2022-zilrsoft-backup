<?php
	
	namespace App\Events\Accounting\Invoice;
	
	use App\Models\Invoice;
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
		
		
		public function broadcastOn()
		{
			
			return new Channel("my-channel");
		}
		
		
	}
