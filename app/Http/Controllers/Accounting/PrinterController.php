<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use App\Models\Invoice;
	use App\Models\Item;
	use App\Models\Payment;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\View\View;

//	use Mpdf\Mpdf;
//	use Picqer\Barcode\BarcodeGeneratorPNG;
	
	class PrinterController extends Controller
	{
	
		
		public function print_a4(Invoice $invoice)
		{
//			return $invoice->items()->groupBy('qty')->selectRaw('warranty_subscription_id')->get();
			
			
			return view('accounting.printer.a4',compact('invoice'));
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
			$invoice = $sale;
			return view('accounting.printer.receipt',compact('invoice'));
		}


		public function voucher(Payment $voucher)
		{

			$payment = $voucher;
			return view('accounting.printer.voucher',compact('voucher','payment'));
		}

	}
