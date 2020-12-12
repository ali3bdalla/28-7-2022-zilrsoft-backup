<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SoapClient;
use Tests\TestCase;

class UseSoapTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
//        $soapClient = new SoapClient("http://track.smsaexpress.com/SeCom/SMSAwebService.asmx");
//
//        // Prepare SoapHeader parameters
//        $sh_param = array(
//            'Username' => 'username',
//            'Password' => 'password');
//        $headers = new SoapHeader('http://soapserver.example.com/webservices', 'UserCredentials', $sh_param);
//
//        // Prepare Soap Client
//        $soapClient->__setSoapHeaders(array($headers));
//
//        // Setup the RemoteFunction parameters
//        $ap_param = array(
//            'amount' => 5352);

        // Call RemoteFunction ()
        $error = 0;
        try {
//            $info = $soapClient->__call("getRTLCities", array($ap_param));
//            dd($info);

            $opts = array(
                'http' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            );
            $context = stream_context_create($opts);

            $wsdlUrl = 'http://track.smsaexpress.com/SeCom/SMSAwebService.asmx?WSDL';
            $soapClientOptions = array(
                'cityCode' => $context,
                'passkey' => WSDL_CACHE_NONE
            );

            $client = new SoapClient($wsdlUrl, $soapClientOptions);

            $checkVatParameters = array(
                'awbNo' => '215053943137'
            );

            $result = $client->getStatus($checkVatParameters);
//            $xml=simplexml_load_string($result->getRTLCitiesResult->schema);
            dd($result);

        } catch (SoapFault $fault) {
            $error = 1;
            throw $fault;
//            print("
//            alert('Sorry, blah returned the following ERROR: " . $fault->faultcode . "-" . $fault->faultstring . ". We will now take you back to our home page.');
//            window.location = 'main.php';
//            ");
        }

//        if ($error == 0) {
//            $auth_num = $info->RemoteFunctionResult;
//
//            if ($auth_num < 0) {
//
//                // Setup the OtherRemoteFunction() parameters
//                $at_param = array(
//                    'amount' => 23,
//                    'description' => "klsdjf");
//
//                // Call OtherRemoteFunction()
//                $trans = $soapClient->__call("OtherRemoteFunction", array($at_param));
//                $trans_result = $trans->OtherRemoteFunctionResult;
//            } else {
//                // Record the transaction error in the database
//
//                // Kill the link to Soap
//                unset($soapClient);
//            }
//        }

    }
}
