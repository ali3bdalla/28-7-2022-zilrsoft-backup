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

class DownloadShippmentPdfJob implements ShouldQueue
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
            ['Content-Type' => 'text/xml;  charset=utf-8', 'charset' => 'utf-8'],
            '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
              <getPDF xmlns="http://track.smsaexpress.com/secom/">
              <passKey>' . env('SMSA_API_KEY') . '</passKey>
              <awbNo>' . $this->shippingTransaction->tracking_number . '</awbNo>
              </getPDF>
            </soap:Body>
          </soap:Envelope>'
        );

        $response = app("SmsaClient")->send($request);
        preg_match("#\<getPDFResult\>(.*)<\/getPDFResult\>#", (string)$response->getBody(), $matches);
        if ($base64 = $matches[1]) {
            $decoded = base64_decode($base64);
            $file = $this->shippingTransaction->id . '_AWB.pdf';
            file_put_contents($file, $decoded);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }
    }
}
