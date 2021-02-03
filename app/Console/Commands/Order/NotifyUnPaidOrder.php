<?php

namespace App\Console\Commands\Order;

use AliAbdalla\Whatsapp\Whatsapp;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUnPaidOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notifyUnPaidOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Is used to cancel Un Paid Order after it time finish';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//

        $orders = Order::where([['status', 'issued'], ['is_should_pay_notified', false]])->whereDate('should_pay_last_notification_at', '<=', Carbon::now())->whereTime('should_pay_last_notification_at', '<=', Carbon::now())->get();
//			Whatsapp::sendMessage("notifyUnPaidOrder ({$ordersCount}) orders " . Carbon::now()->toDateTimeString(), "249966324018");
        foreach ($orders as $order) {
            $phoneNumber = $order->user->phone_number;
            $order->update(
                [
                    'is_should_pay_notified' => true
                ]
            );

            $messageTemplate = view(
                'whatsapp.order_will_be_canceled_notify', [
                    'order' => $order,
                    'date' => Carbon::parse($order->auto_cancel_at)->format('H:i')
                ]
            )->toHtml();
            sendSms($messageTemplate, $phoneNumber);
//            Whatsapp::sendMessage($messageTemplate, $phoneNumber);
        }

    }


}
