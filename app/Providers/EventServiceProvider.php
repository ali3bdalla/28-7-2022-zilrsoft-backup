<?php

namespace App\Providers;

use App\Events\ChartCreatedEvent;
use App\Events\Transaction\TransactionCreatedEvent;
use App\Events\Transaction\TransactionErasedEvent;
use App\Events\User\ShouldUpdateUserBalanceEvent;
use App\Listeners\Account\UpdateAccountStatisticListener;
use App\Listeners\CreatePaymentGatewayListener;
use App\Listeners\User\UpdateUserBalanceListener;
use Illuminate\Support\Facades\Event;
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
        'App\Events\UserCreatedEvent'=>[
            'App\Listeners\UserCreatedListener'
        ],
	    ChartCreatedEvent::class => [
	        CreatePaymentGatewayListener::class
	    ],
        TransactionCreatedEvent::class => [
            UpdateAccountStatisticListener::class,
        ],
        TransactionErasedEvent::class => [
            UpdateAccountStatisticListener::class,
        ],
        ShouldUpdateUserBalanceEvent::class => [
            UpdateUserBalanceListener::class
        ]
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
