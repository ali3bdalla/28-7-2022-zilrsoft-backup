<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Account;
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Observers\OrganizationObServer;
use App\Observers\TransactionObserver;
use App\Models\Organization;
use App\Models\Transaction;
use App\Observers\AccountObserver;
class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // Transaction::observe(TransactionObserver::class);
        // Organization::observe(OrganizationObServer::class);
        // Account::observe(AccountObserver::class);
        // Category::observe(CategoryObserver::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
       
    }
}
