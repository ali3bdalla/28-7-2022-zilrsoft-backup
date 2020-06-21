<?php

namespace App\Listeners\User;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUserBalanceListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->balance_type == 'client_balance')
            $this->toUpdateClientBalance($event->user,$event->operator,$event->amount);
        else
            $this->toUpdateVendorBalance($event->user,$event->operator,$event->amount);

    }

    public function toUpdateVendorBalance($identity,$option,$amount)
    {
        $amount = floatval($amount);
        $old_balance = $identity->vendor_balance;
        if ($option == 'add'){
            $identity->update([
                'vendor_balance' => $old_balance + $amount
            ]);
            return true;
        }else{
            $identity->update([
                'vendor_balance' => $old_balance - $amount
            ]);
            return true;
        }
        return false;
    }

    /**
     * @param $identity
     * @param $option
     * @param $amount
     *
     * @return bool
     */
    public function toUpdateClientBalance($identity,$option,$amount)
    {
        $amount = floatval($amount);
        $old_balance = $identity->balance;
        if ($option == 'add'){
            $identity->update([
                'balance' => $old_balance + $amount
            ]);
            return true;
        }else{
            $identity->update([
                'balance' => $old_balance - $amount
            ]);
            return true;
        }

        return false;
    }

}
