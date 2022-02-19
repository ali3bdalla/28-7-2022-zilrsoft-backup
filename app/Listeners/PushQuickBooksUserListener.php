<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Jobs\QuickBooks\CustomerSyncJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class PushQuickBooksUserListener
{

    /**
     * Handle the event.
     *
     * @param object $event
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

        if ($event->user->is_client) dispatch(new CustomerSyncJob($event->user, $manager));
    }
}
