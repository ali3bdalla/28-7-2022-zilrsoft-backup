<?php

namespace App\Listeners\Models\Account;

class UpdateAccountDetailsListener
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
        if ($event->account->parent) {
            $event->account->parent->updateHashMap();
        }
        $event->account->updateHashMap();
        $event->account->updateSerial();
    }
}
