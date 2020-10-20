<?php

namespace App\Listeners\Models\Transaction;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;


class UpdateTransactionDetailsListener
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
        //
        if($event->transaction->is_pending == false)
        {
            dispatch(new UpdateAccountBalanceJob($event->transaction));
        }
    }
}
