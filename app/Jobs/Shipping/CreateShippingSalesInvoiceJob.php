<?php

namespace App\Jobs\Shipping;

use App\Jobs\Sale\CreateSalesJob;
use App\Models\ShippingTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class CreateShippingSalesInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shippingTransaction,$user,$amount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( User $user,ShippingTransaction $shippingTransaction,$amount = 0)
    {
        $this->shippingTransaction = $shippingTransaction;
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->shippingTransaction->shippingMethod->item) 
        {
            $item = $this->shippingTransaction->shippingMethod->item;

            
            $item->qty = 1;
            $item->price = moneyFormatter(($this->amount / 1.15));
            $item->discount = 0;
            CreateSalesJob::dispatchNow($this->user->id,[$item->toArray()]);
            
        }
    }
}
