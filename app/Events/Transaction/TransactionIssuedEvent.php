<?php
	
	namespace App\Events\Transaction;
	
	use App\Models\ResellerClosingAccount;
	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	
	class TransactionIssuedEvent implements ShouldBroadcastNow
	{
		use Dispatchable, InteractsWithSockets, SerializesModels;
		
		/**
		 * @var ResellerClosingAccount
		 */
		public $transaction;
		
		/**
		 * Create a new event instance.
		 *
		 * @param ResellerClosingAccount $resellerClosingAccount
		 */
		public function __construct(ResellerClosingAccount $resellerClosingAccount)
		{
			$this->transaction = $resellerClosingAccount->load('creator', 'fromAccount', 'toAccount', 'receiver');
		}
		
		public function broadcastAs()
		{
			return 'transaction-issued';
		}
		
		/**
		 * Get the channels the event should broadcast on.
		 *
		 * @return Channel|array
		 */
		public function broadcastOn()
		{
			return new PrivateChannel('transaction-issued');
		}
	}
