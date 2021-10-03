<?php

namespace App\Providers;

use App\Events\Item\ItemUpdatedEvent;
use App\Events\Models\Account\AccountCreated;
use App\Events\Models\Account\AccountUpdated;
use App\Events\Models\Transaction\TransactionCreated;
use App\Listeners\Item\UpdateItemSlugListener;
use App\Listeners\Models\Account\UpdateAccountDetailsListener;
use App\Listeners\Models\Transaction\UpdateTransactionDetailsListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Verified' => [
            'App\Listeners\LogVerifiedUser',
        ],
        AccountCreated::class => [
            UpdateAccountDetailsListener::class
        ],
        AccountUpdated::class => [
            UpdateAccountDetailsListener::class
        ],
        TransactionCreated::class => [
            UpdateTransactionDetailsListener::class
        ],
        ItemUpdatedEvent::class => [
            UpdateItemSlugListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }
}
