<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Models\Order;
use App\Models\User;
use App\Package\Whatsapp;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderWillBeCanceledNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class];
    }

    public function toWhatsappMessage($notifiable): string
    {
        app()->setLocale($this->order->lang);
        return __('store.messages.notify_unpaid_order_message',
            ["ORDERID" => $this->order->id, 'DATE' => Carbon::parse($this->order->auto_cancel_at)->toDateString(), 'TIME' => Carbon::parse($this->order->auto_cancel_at)->toTimeString()]);
    }
}
