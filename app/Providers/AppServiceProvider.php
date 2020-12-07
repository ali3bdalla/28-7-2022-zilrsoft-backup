<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

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

        if ($this->app->isLocal()) {
//            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }


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
