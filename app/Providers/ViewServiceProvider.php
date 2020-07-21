<?php

namespace App\Providers;

use App\Invoice;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        View::share('organization_config', [
            'vts' => 15,
            'vtp' => 15
        ]);



    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //

        Blade::directive('js_asset', function ($file) {
            $file = str_replace(['(', ')', "'"], '', $file);
            return '<script src="' . asset($file) . '" ></script>';
        });

        Blade::directive('css_asset', function ($file) {
            $file = str_replace(['(', ')', "'"], '', $file);
            return '<link href="' . asset($file) . '" rel="stylesheet" />';
        });


        Blade::directive('defer_js_asset', function ($file) {
            $file = str_replace(['(', ')', "'"], '', $file);
            return '<script src="' . asset($file) . '" defer></script>';
        });




        Blade::directive('meta', function ($arguments) {

            list($name,$content)  = $arguments;

            return '<meta name="' . $name . '" content="' . $content . '">';
        });


    }


}
