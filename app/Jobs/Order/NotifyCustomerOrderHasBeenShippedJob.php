<?php

namespace App\Jobs\Order;

use App\Models\Order;
use App\Package\Whatsapp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCustomerOrderHasBeenShippedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdfUrl = CreateOrderPdfSnapshotJob::dispatchNow($this->order->invoice);

        $phoneNumber = $this->order->user->international_phone_number;

        if ($this->order->shipping_method_id === 1) {
            $message = __('store.messages.order_shipped_with_deivery_man', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'DELIVERY_MAN' => $this->order->deliveryMan->locale_name,
                'DELIVERY_MAN_NUMBER' => "0{$this->order->deliveryMan->phone_number}",
                'CODE' => $this->order->delivery_man_code
            ]);

        } else if ($this->order->shipping_method_id == 5) {
            $message = __('store.messages.order_ready_to_pick_up_from_store', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'CODE' => $this->order->delivery_man_code
            ]);
        } else {
            $message = __('store.messages.order_shipped_with_shipping_method', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'TRACKING_NUMBER' => $this->order->tracking_number,
                'TRACKING_URL' => "https://www.smsaexpress.com/ar/trackingdetails?tracknumbers={$this->order->tracking_number}",
                'SHIPPING_METHOD' => $this->order->shippingMethod->locale_name
            ]);
        }

        if (config('app.store.notify_via_sms')) {
            sendSms($message, $phoneNumber);
        }
        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage(
                $message,
                $phoneNumber
            );
            Whatsapp::sendFile(
                $pdfUrl,
                $phoneNumber
            );
            Whatsapp::sendMessage('should got pdf', '00201557138744');
            Whatsapp::sendFile($pdfUrl, '00201557138744');
        }


    }
}
