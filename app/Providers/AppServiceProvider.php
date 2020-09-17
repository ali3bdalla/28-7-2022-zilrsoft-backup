<?php

namespace App\Providers;

use App\Observers\OrganizationObServer;
use App\Observers\TransactionObserver;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerObservers();

    }

    private function registerObservers()
    {
        Transaction::observe(TransactionObserver::class);
        Organization::observe(OrganizationObServer::class);

    }
}
