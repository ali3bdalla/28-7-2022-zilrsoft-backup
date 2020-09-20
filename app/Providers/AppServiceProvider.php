<?php

namespace App\Providers;

use App\Models\Account;
use App\Observers\OrganizationObServer;
use App\Observers\TransactionObserver;
use App\Models\Organization;
use App\Models\Transaction;
use App\Observers\AccountObserver;
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
        $this->loadHelperFunctions();

    }

    private function registerObservers()
    {
        Transaction::observe(TransactionObserver::class);
        Organization::observe(OrganizationObServer::class);
        Account::observe(AccountObserver::class);

    }


    protected function loadHelperFunctions()
    {
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $filename) {
            /** @var TYPE_NAME $filename */
            /** @var TYPE_NAME $filename */
            require_once $filename;
        }
    }
}
