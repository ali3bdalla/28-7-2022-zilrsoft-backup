<?php
	
	namespace App\Events\Order;
	
	use App\Models\Invoice;
	use App\Models\Order;
	use App\Models\User;
	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	
	class OrderCreatedEvent
	{
		use Dispatchable, InteractsWithSockets, SerializesModels;
		
		/**
		 * @var Invoice
		 */
		public $invoice;
		public $client;
		public $order;
		public $path;
		
		/**
		 * Create a new event instance.
		 *
		 * @param Invoice $invoice
		 * @param $path
		 */
		public function __construct(Invoice $invoice,$path)
		{
			
			$this->invoice = $invoice;
			$this->order = Order::where('draft_id', $invoice->id)->first();
			$this->client = User::find($invoice->user_id);
			
			$this->path = $path;
		}
		
		/**
		 * Get the channels the event should broadcast on.
		 *
		 * @return Channel|array
		 */
		public function broadcastOn()
		{
			return new PrivateChannel('channel-name');
		}
	}
