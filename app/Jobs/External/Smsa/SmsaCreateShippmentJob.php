<?php

namespace App\Jobs\External\Smsa;

use CodeDredd\Soap\Facades\Soap;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsaCreateShippmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $shippmentData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shippmentData = [])
    {
        $this->shippmentData = $shippmentData;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // try {\

        // $client = Soap::buildClient('smsa_soap');
        $response = app('SmsaClient')->addShip(array(
            "passKey" => "Testing1",
            "refNo" => '53',

            "idNo" => '300189',
            "cName" => "ali abdalla",
            "sCntry" => "KSA",
            "cCity" => "Qaseem",
            "cMobile" => "556945415",
            "cAddr1" => "Suadia arebia - alrass",
            // "shipType" => "DLV",
            "codAmt" => 0,
            "PCs" => 45,
            "cEmail" => "test@gtest.com",
            "carrValue" => "sar",
            "insrCurr" => "sar",
            "itemDesc" => "iphone 6",
            "weight" => "1",
            "sName" => "Bait Amesbar",
            "sContact" => "Mahmoud",
            "sAddr1" => "Mahmoud",
            "sCity" => "Ar Rass",
            "sPhone" => "556045415"

        ));

        // $response = $client->call("getRTLCities");

        dd($response->body());

        // dd($result);
        // $data = simplexml_load_string($result->getRTLCitiesResult->any);
        // return $data;
        // } catch (\Throwable $th) {
        //     return [];
        // }
    }
}
