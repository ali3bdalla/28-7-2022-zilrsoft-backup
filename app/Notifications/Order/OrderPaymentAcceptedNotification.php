<?php

namespace App\Notifications\Order;

use App\Channels\BroadcastNotificationContract;
use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Dto\BroadcastNotificationDto;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderPaymentAcceptedNotification extends Notification implements
    OurSmsNotificationContract,
    WhatsappMessageNotificationContract,
    ShouldQueue,
    BroadcastNotificationContract
{
    use Queueable;

    private Order $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class,OurSmsChannel::class,'database','broadcast'];
    }

    public function toOurSms($notifiable): string
    {
        return $this->toWhatsappMessage($notifiable);
    }

    public function toWhatsappMessage($notifiable): string
    {
        return __('store.messages.order_payment_confirmed', [
            'CUSTOMER_NAME' => $this->order->user->name,
            'ORDER_ID' => $this->order->id,
        ]);
    }

    public function toBroadcast($notifiable): BroadcastNotificationDto
    {
        return new BroadcastNotificationDto(
            $this->toWhatsappMessage($notifiable),
            []
        );
    }
}
