<?php

namespace App\Providers;

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

        $this->loadHelperFunctions();

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
