<?php

namespace App\Providers;

use CodeDredd\Soap\Facades\Soap;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
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
            $client = new Client(['base_uri' => 'https://track.smsaexpress.com/SeCom/SMSAwebService.asmx?wsdl' ,'headers' => ['Content-Type' => "text/xml", "charset" => "utf-8"]]);
            return $client;
            // new Client()

            // return  Soap::buildClient("smsa_soap");
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
