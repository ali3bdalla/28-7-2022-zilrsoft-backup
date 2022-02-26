<?php

namespace App\Listeners;

use App\Events\ItemCreatedEvent;
use App\Jobs\QuickBooks\ItemQuickBooksSyncJob;
use Illuminate\Support\Facades\Auth;

class PushQuickBooksItemListener
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
     * @param object $event
     * @return void
     */
    public function handle(ItemCreatedEvent $event)
    {
        $manager = null;
        if (Auth::check() && Auth::user()->quickBooksToken) {
            $manager = Auth::user();
        }
        if (!$manager) {
            $manager = $event->item->organization->managers()->whereHas("quickBooksToken")->first();
        }
        if (!$manager) return;
        dispatch(new ItemQuickBooksSyncJob($event->item, $manager));
    }
}
