<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderHasBeenCanceledNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
{
    use Queueable;

    private Order $order;
    private bool $isManual;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, bool $isManual = false)
    {
        //
        $this->order = $order;
        $this->isManual = $isManual;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class];
    }

    public function toWhatsappMessage($notifiable): string
    {
        return __("store.messages.unpaid_order_canceled_message", ["CUSTOMER_NAME" => $this->order->user->name, "ORDERID" =>
            $this->order->id, 'REASON' => $this->isManual ? __('store.messages.as_your_request') : __('store.messages.not_paid')]);
    }
}
