<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CanceledOrderNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
{
    use Queueable;

    private Order $order;
    /**
     * @var false
     */
    private bool $userRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, $userRequest = false)
    {
        //
        $this->order = $order;
        $this->userRequest = $userRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsappMessageChannel::class];
    }

    public function toWhatsappMessage($notifiable): string
    {
        return __("store.messages.unpaid_order_canceled_message",
            [
                "CUSTOMER_NAME" => $this->order->user->name,
                "ORDERID" => $this->order->id,
                'REASON' => $this->userRequest ? __('store.messages.as_your_request') : __('store.messages.not_paid')
            ]);
    }
}
