<?php

namespace App\Listeners\Item;

use App\Events\Item\ItemUpdatedEvent;
use App\Jobs\Items\Google\UpdateGoogleShippingItemJob;


class UpdateGoogleRecordListener
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
     * @param ItemUpdatedEvent $event
     * @return void
     */
    public function handle(ItemUpdatedEvent $event)
    {
        UpdateGoogleShippingItemJob::dispatch($event->item);
    }
}
