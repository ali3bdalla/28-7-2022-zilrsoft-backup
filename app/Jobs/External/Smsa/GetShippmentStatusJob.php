<?php

namespace App\Jobs\External\Smsa;

use App\Models\City;
use App\Models\ShippingTransaction;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Psr7\Request;

class GetShippmentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shippingTransaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ShippingTransaction $shippingTransaction)
    {
        $this->shippingTransaction = $shippingTransaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request(
            'POST',
            'https://track.smsaexpress.com/SeCom/SMSAwebService.asmx',
            ['Content-Type' => 'text/xml;  charset=utf-8', 'charset' => 'utf-8','SOAPAction' => "http://track.smsaexpress.com/secom/getStatus"],
            '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
              <getStatus xmlns="http://track.smsaexpress.com/secom/">
                <awbNo>290133752655</awbNo>
                <passKey>Testing1</passKey>
              </getStatus>
            </soap:Body>
          </soap:Envelope>'
        );

        $response = app("SmsaClient")->send($request);
        dd((string)$response->getBody());
        // dd($response->getBody());
        return  (string)$response->getBody();
        // preg_match("#\<getStatusResult\>(.*)<\/getStatusResult\>#", (string)$response->getBody(), $matches);
        // return  (string)$response->getBody();
        // if ($status = $matches[1]) {
        //     return  (string)$response->getBody();
        //     return $status;
        // }
        
        // return $this->shippingTransaction->status;
    }
}
