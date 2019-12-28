<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Invoice;
	use App\Item;
	use App\Payment;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\View\View;

//	use Mpdf\Mpdf;
//	use Picqer\Barcode\BarcodeGeneratorPNG;
	
	class PrinterController extends Controller
	{
		
		public function sale_receipt(Invoice $invoice)
		{
			return view('template.receipt.pos2',compact('invoice'));
		}
		
		/**
		 * @param Request $request
		 *
		 * @return string
		 */
		public function sign_receipt_printer(Request $request)
		{
			
			$KEY = public_path("accounting/key/private-key.pem");
			$req = $request->input('request');
			$privateKey = openssl_get_privatekey(file_get_contents($KEY));
			$signature = null;
			openssl_sign($req,$signature,$privateKey);
			if ($signature){
				header("Content-type: text/plain");
				return base64_encode($signature);
			}
			
		}
		
		/**
		 * @return Factory|View
		 */
		public function printers()
		{
			return view('accounting.printer.printers');
		}
		
		public function print_receipt(Invoice $sale)
		{
			$invoice =  $sale;
			return view('accounting.printer.receipt',compact('invoice'));
		}

//
//		public function barcode(Item $item)
//		{
//
//		}
//
//		public function voucher(Payment $voucher)
//		{
//
//			$payment = $voucher;
//			return view('template.a4.voucher',compact('voucher','payment'));
//		}
//
//		public function a4(Invoice $invoice)
//		{
////		return $invoice;
//
////		$mpdf = new Mpdf();
////
////		$mpdf->SetHeader('hello');
////		$view = view('template.a4.invoice',compact('invoice'));
////		$mpdf->WriteHTML($view);
////		return  $mpdf->Output();
//
//			return view('template.a4.invoice',compact('invoice'));
//		}
//
//		public function sign()
//		{
//			$KEY = storage_path("private-key.pem");
////		return $KEY;
//
//			$req = $_REQUEST['request']; //GET method
//
//			$privateKey = openssl_get_privatekey(file_get_contents($KEY));
//
//			$signature = null;
//			openssl_sign($req,$signature,$privateKey);
//
//			if ($signature){
//				header("Content-type: text/plain");
//				return base64_encode($signature);
////			exit(0);
//			}
//		}
		
	}
