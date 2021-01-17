<?php

namespace App\Providers;

use CodeDredd\Soap\Facades\Soap;
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

        $this->app->bind("SmsaClient", function ($app) {
            return  Soap::buildClient("smsa_soap");
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadHelperFunctions();

    }

    protected function loadHelperFunctions()
    {
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $filename) {
            include_once("{$filename}");
        }
    }


}
