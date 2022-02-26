<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Jobs\QuickBooks\CustomerQuickBooksSyncJob;
use App\Jobs\QuickBooks\CustomerSyncJob;
use App\Jobs\QuickBooks\VendorQuickBooksSyncJob;
use Illuminate\Support\Facades\Auth;

class PushQuickBooksUserListener
{

    /**
     * Handle the event.
     *
     * @param UserCreatedEvent $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $manager = null;
        if (Auth::check() && Auth::user()->quickBooksToken) {
            $manager = Auth::user();
        }

        if (!$manager) {
            $manager = $event->user->organization->managers()->whereHas("quickBooksToken")->first();
        }

        if (!$manager) return;

        if ($event->user->is_client) dispatch(new CustomerQuickBooksSyncJob($event->user, $manager));
        if ($event->user->is_vendor) dispatch(new VendorQuickBooksSyncJob($event->user, $manager));
    }
}
