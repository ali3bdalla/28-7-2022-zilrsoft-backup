<?php

namespace App\Notifications\Order;

use App\Channels\BroadcastNotificationContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPaidOrderNotification extends Notification implements ShouldQueue, BroadcastNotificationContract
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
        return ['database', "broadcast"];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        $data = $this->toArray($notifiable);
        return new BroadcastMessage([
            'message' => $data['message'],
            'actions' => $data['actions']
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => 'طلب من المتجر الاونلاين',
            'actions' => [
                [
                    "title" => "قبول",
                    "action" => "/store/orders/{$this->order->id}/accept-order-as-manager",
                    "method" => "post"
                ],
                [
                    "title" => "اخفاء الاشعار",
                    "action" => "#",
                    "method" => "post"
                ]
            ]
        ];
    }


}
