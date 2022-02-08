<?php

namespace App\Providers;

use App\Rules\QuantityValidationRule;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
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
            return new Client(['base_uri' => 'https://track.smsaexpress.com/SeCom/SMSAwebService.asmx?wsdl', 'headers' => ['Content-Type' => "text/xml", "charset" => "utf-8"]]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('quantity', QuantityValidationRule::class);

    }
}
