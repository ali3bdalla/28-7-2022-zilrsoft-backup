<?php

namespace App\Jobs\Order;

use App\Models\Order;
use App\Package\Whatsapp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NotifyCustomerOrderHasBeenShippedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @throws TransportExceptionInterface
     */
    public function handle()
    {
        $pdfUrl = CreateOrderPdfSnapshotJob::dispatchNow($this->order->invoice);

        $phoneNumber = $this->order->user->international_phone_number;
        $deliveryMan = $this->order->deliveryMan;
        $deliveryManPhoneNumber = $deliveryMan ? $deliveryMan->phone_number : '';
        if (1 === $this->order->shipping_method_id) {
            $message = __('store.messages.order_shipped_with_deivery_man', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'DELIVERY_MAN' => $this->order->deliveryMan ? $this->order->deliveryMan->locale_name : '',
                'DELIVERY_MAN_NUMBER' => "0{$deliveryManPhoneNumber}",
                'CODE' => $this->order->delivery_man_code,
            ]);
        } elseif (5 == $this->order->shipping_method_id) {
            $message = __('store.messages.order_ready_to_pick_up_from_store', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'CODE' => $this->order->delivery_man_code,
            ]);
        } else {
            $message = __('store.messages.order_shipped_with_shipping_method', [
                'CUSTOMER_NAME' => $this->order->user->name,
                'ORDER_ID' => $this->order->id,
                'TRACKING_NUMBER' => $this->order->tracking_number,
                'TRACKING_URL' => file_get_contents("http://tinyurl.com/api-create.php?url=https://www.smsaexpress.com/ar/trackingdetails?tracknumbers={$this->order->tracking_number}"),
                'SHIPPING_METHOD' => $this->order->shippingMethod ? $this->order->shippingMethod->locale_name : '',
            ]);
        }

        if (config('app.store.notify_via_sms')) {
            sendSms($message, $phoneNumber);
        }
        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage(
                $message,
                $phoneNumber,
                false
            );
            Whatsapp::sendFile(
                $pdfUrl,
                $phoneNumber,
                $this->order->id.'.pdf'
            );
        }
    }
}
