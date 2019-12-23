<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Item;
use App\Payment;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PrinterController extends Controller
{
	
	public function sale_receipt(Invoice $invoice)
	{
		return view('template.receipt.pos2',compact('invoice'));
	}
	
	
	public function barcode(Item $item)
	{
		
		
//		return $item;
	}
	
	
	
	
	
	public function voucher(Payment $voucher)
	{
		
		$payment = $voucher;
		return view('template.a4.voucher',compact('voucher','payment'));
	}
	
	public function a4(Invoice $invoice)
	{
//		return $invoice;
		
//		$mpdf = new Mpdf();
//
//		$mpdf->SetHeader('hello');
//		$view = view('template.a4.invoice',compact('invoice'));
//		$mpdf->WriteHTML($view);
//		return  $mpdf->Output();
		
		return view('template.a4.invoice',compact('invoice'));
	}
	
	
	
	public function sign()
	{
		$KEY = storage_path("private-key.pem");
//		return $KEY;
		
		$req = $_REQUEST['request']; //GET method
		
		$privateKey = openssl_get_privatekey(file_get_contents($KEY));
		
		$signature = null;
		openssl_sign($req, $signature, $privateKey);
		
		if ($signature) {
			header("Content-type: text/plain");
			return base64_encode($signature);
//			exit(0);
		}
	}
 
}