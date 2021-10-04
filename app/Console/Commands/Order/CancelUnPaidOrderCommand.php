<?php

namespace App\Console\Commands\Order;

use App\Notifications\Store\OrderHasBeenCanceledNotification;
use App\Repository\OrderRepositoryContract;
use Illuminate\Console\Command;

class CancelUnPaidOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cancelUnPaidOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Is used to cancel Un Paid Order after it time finish';
    private OrderRepositoryContract $orderRepositoryContract;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderRepositoryContract $orderRepositoryContract)
    {
        parent::__construct();
        $this->orderRepositoryContract = $orderRepositoryContract;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $orders = $this->orderRepositoryContract->getNotifiedUnPaidOrders();
        foreach ($orders as $order) {
            $order->markAsCanceled();
            $order->user->notify(new OrderHasBeenCanceledNotification($order));
        }
        return 0;
    }


}
