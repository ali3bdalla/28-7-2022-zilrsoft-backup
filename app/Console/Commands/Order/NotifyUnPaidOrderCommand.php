<?php

namespace App\Console\Commands\Order;

//use AliAbdalla\Whatsapp\Whatsapp;
use App\Models\Order;
use App\Package\Whatsapp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUnPaidOrderCommand extends Command
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
        $orders = Order::where([['status', 'issued'], ['is_should_pay_notified', false]])->whereDate('should_pay_last_notification_at', '<=', Carbon::now())->whereTime('should_pay_last_notification_at', '<=', Carbon::now())->get();
        foreach ($orders as $order) {
            $order->update(
                [
                    'is_should_pay_notified' => true
                ]
            );
            $message = __('store.messages.notify_unpaid_order_message',
                ["ORDERID" => $order->id, 'DATE' => Carbon::parse($order->auto_cancel_at)->toDateString(), 'TIME' => Carbon::parse($order->auto_cancel_at)->toTimeString()]);

            $userPhoneNumber = $order->user->international_phone_number;
            if (config('app.store.notify_via_whatsapp')) {
                Whatsapp::sendMessage($message, $userPhoneNumber);
            }
            if (config('app.store.notify_via_sms')) {
                sendSms($message, $userPhoneNumber);
            }

        }
    }


}
