<?php

namespace App\Jobs\Smsa;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSmsaShippmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $opts = array(
                'http' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            );
            $context = stream_context_create($opts);

            $wsdlUrl = 'http://track.smsaexpress.com/SeCom/SMSAwebService.asmx?WSDL';
            $soapClientOptions = array(
//                'cityCode' => $context,
//                'passKey' => "Testing1",
//                'refNo' => "Testing1",
//                'sentDate' => "Testing1",
//                'passKey' => "Testing1",
//                'passKey' => "Testing1",
//                'passKey' => "Testing1",
//                'passKey' => "Testing1",
//                'passKey' => "Testing1",
//                'passKey' => "Testing1",
            );

            $client = new SoapClient($wsdlUrl, $soapClientOptions);

            $checkVatParameters = array(
                'cityCode' => 'RAS',
                'vatNumber' => '47458714'
            );

            $result = $client->getRTLCities($checkVatParameters);
//            $xml=simplexml_load_string($result->getRTLCitiesResult->schema);
            dd($result);
        }catch (Exception $exception) {

        }
    }
}
