<?php

namespace App\Notifications\WarrantyTracing;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use App\Enums\InvoiceItemStatusEnum;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WarrantyTracingUpdateNotification extends Notification implements WhatsappMessageNotificationContract, ShouldQueue
{
    use Queueable;
    private Invoice $invoice;
    private InvoiceItemStatusEnum $invoiceItemStatusEnum;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, InvoiceItemStatusEnum $invoiceItemStatusEnum)
    {
        $this->invoice = $invoice;
        $this->invoiceItemStatusEnum = $invoiceItemStatusEnum;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->mobile || !$this->invoice->contact_phone_number)
            return [WhatsappMessageChannel::class];
        return [];
    }

    public function getWhatsappNumber($notification)
    {
        return $notification->user->mobile;
    }
    public function toWhatsappMessage($notifiable): String
    {
        return "تم تحديث حالة سند تتبع الضمان رقم {$this->invoice->id} الي : {$this->invoiceItemStatusEnum->label}";
    }
}
