<?php
namespace App\Providers\Plugin;

use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class PDFProvider {

    private $mPdf;
    public function __construct()
    {
        $this->mPdf  = new mPDF([
            'mode' => 'ar',
            'format' => 'A4',
            'setAutoBottomMargin'  => 'stretch',

        ]);


        return $this;
    }


    public function createPaymentPdf($html,$id = 0,$starter = 'payment_'){
        if(!is_dir(public_path('app/public/payments/')))
            Storage::makeDirectory('public/payments');


        $inv = $starter . base64_encode($id) . '.pdf';
        $path = 'app/public/payments/' . $inv;
        $this->mPdf->WriteHTML($html);
        $this->mPdf->Output(storage_path($path), \Mpdf\Output\Destination::FILE);
        return Storage::url('public/payments/' . $inv);
    }



    public function storeInvoicePDF($html,$id = 0){
        if(!is_dir(public_path('app/public/pdf')))
             Storage::makeDirectory('public/pdf');


        $inv = 'invoice_' . base64_encode($id) . '.pdf';
        $path = 'app/public/pdf/' . $inv;
        $this->mPdf->WriteHTML($html);
        $this->mPdf->Output(storage_path($path), \Mpdf\Output\Destination::FILE);
        return 'public/pdf/' . $inv;
    }



}
