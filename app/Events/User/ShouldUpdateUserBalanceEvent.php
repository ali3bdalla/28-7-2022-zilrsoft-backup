<?php

namespace App\Events\User;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShouldUpdateUserBalanceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $balance_type;
    /**
     * @var string
     */
    public $operator;
    /**
     * @var int
     */
    public $amount;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param int $amount
     * @param string $balance_type
     * @param string $operator
     */
    public function __construct(User $user,$amount = 0,$balance_type = "client_balance",$operator = 'add')
    {
        //
        $this->user = $user;
        $this->balance_type = $balance_type;
        $this->operator = $operator;
        $this->amount = $amount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
