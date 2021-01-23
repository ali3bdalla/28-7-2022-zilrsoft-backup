<?php

namespace App\Jobs\External\Smsa;

use App\Models\City;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Psr7\Request;


class SmsaCreateShippmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $country_code;

    private $shippmentData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shippmentData = [])
    {
        $this->country_code = __('store.common.internationalKey');;
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

        $city = City::findOrFail($this->shippmentData['city_id']);
        $request = new Request(
            'POST',
            'https://track.smsaexpress.com/SeCom/SMSAwebService.asmx',
            ['Content-Type' => 'text/xml;  charset=utf-8','charset' => 'utf-8'],
            '<SOAP-ENV:Envelope encoding = "UTF-8" xmlns:SOAP-ENV="http://www.w3.org/2003/05/soap-envelope" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://track.smsaexpress.com/secom/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" >
            <SOAP-ENV:Body>
                <tns:addShipment xmlns:tns="http://track.smsaexpress.com/secom/">
                    <tns:passKey>'.env('SMSA_API_KEY').'</tns:passKey>
                    <tns:refNo>'.$this->shippmentData['reference'].'</tns:refNo>
                    <tns:sentDate></tns:sentDate>
                    <tns:idNo>'.$this->shippmentData['reference'].'</tns:idNo>
                    <tns:cName>'.$this->shippmentData['first_name'] . ' ' . $this->shippmentData['last_name'].'</tns:cName>
                    <tns:cntry>'.$city->country->name.'</tns:cntry>
                    <tns:cCity>'.$city->locale_name.'</tns:cCity>
                    <tns:cZip></tns:cZip>
                    <tns:cPOBox></tns:cPOBox>
                    <tns:cMobile>'.$this->country_code . '' . $this->shippmentData['phone_number'].'</tns:cMobile>
                    <tns:cTel1></tns:cTel1>
                    <tns:cTel2></tns:cTel2>
                    <tns:cAddr1>sdfdsfsdf</tns:cAddr1>
                    <tns:cAddr2></tns:cAddr2>
                    <tns:shipType>DLV</tns:shipType>
                    <tns:PCs>1</tns:PCs>
                    <tns:cEmail></tns:cEmail>
                    <tns:carrValue></tns:carrValue>
                    <tns:carrCurr></tns:carrCurr>
                    <tns:codAmt>0</tns:codAmt>
                    <tns:weight>'.$this->shippmentData['weight'].'</tns:weight>
                    <tns:custVal></tns:custVal>
                    <tns:custCurr></tns:custCurr>
                    <tns:insrAmt></tns:insrAmt>
                    <tns:insrCurr></tns:insrCurr>
                    <tns:itemDesc>الكترونيات</tns:itemDesc>
                </tns:addShipment>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>'
        );

        $response = app("SmsaClient")->send($request);
        preg_match("#\<addShipmentResult\>(.*)<\/addShipmentResult\>#",(string)$response->getBody(),$matches);
        if($trackingNumber = $matches[1]) 
        {
            return $trackingNumber;       
        }
        






        return "";
    }
}
