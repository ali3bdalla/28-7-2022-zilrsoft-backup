<?php

namespace App\Notifications\Order;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPaidOrderNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
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
        //
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
        return [WhatsappMessageChannel::class, 'database', "broadcast"];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->getNotificationArray());
    }

    private function getNotificationArray(): array
    {
        return [
            'message' => 'طلب من المتجر الاونلاين',
            'actions' => [
                "title" => "قبول",
                "action" => "/store/orders/{$this->order->id}/accept-order-as-manager",
                "method" => "post"
            ]
        ];
    }

    public function toDatabase()
    {
        return json_encode($this->getNotificationArray());
    }

    public function toWhatsappMessage($notifiable): string
    {
        $url = url("/store/orders/{$this->order->id}/accept-order-as-manager");
        return "طلب جديد من المتجر الالكتروني
قبول الطلب: $url
        ";
    }
}
