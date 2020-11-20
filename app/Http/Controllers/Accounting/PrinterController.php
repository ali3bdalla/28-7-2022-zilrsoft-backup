<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use AliAbdalla\PDF\APDFCore;
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
			$pdfInvoice = new APDFCore("decentblue");
			
			
//			$pdfInvoice->setLogo(auth()->user()->organization->logo);
			$pdfInvoice->setType("Sales Invoice");
			$pdfInvoice->setDirection('rtl');
			$pdfInvoice->setLang('ar');
//			$pdfInvoice->SetDirectionality('rtl');
			$pdfInvoice->setReference($invoice->invoice_number);
			$pdfInvoice->setDate(date('M dS ,Y', time()));
			$pdfInvoice->setDue(date('M dS ,Y', strtotime('+3 months')));
			$pdfInvoice->setFrom(array(auth()->user()->organization->getTranslate('title','ar'), "Citroën", "128 AA Juanita Ave", "Île-de-France , DE 91740", "France"));
			$pdfInvoice->setTo(array("Nom de l'acheteur", "Sanofi-Synthélabo", "128 AA Juanita Ave", "Île-de-France , DE 91740", "France"));
	
			
			$pdfInvoice->addItem("AMD Athlon X2DC-7450", "2.4GHz/1GB/160GB/SMP-DVD/VB", 1, "50%", 100, "50%");
			$pdfInvoice->addItem("PDC-E5300", "2.6GHz/1GB/320GB/SMP-DVD/FDD/VB", 1, 50, 100, 50);
			$pdfInvoice->addItem('LG 18.5" WLCD', "Test multilingue soutenu dans cette section en ajoutant personnalisée description du produit ici", 1, 0, 100, 0);
			$pdfInvoice->addItem("HP LaserJet (Citroën) źłóźśćąę ", "Ceci est une description de test pour le produit HP LaserJet 5200", 1, 0, 100, 20);
			
			// Make sure to add  "$invoice->items_total" first before adding other "addTotal()"
			$pdfInvoice->addTotal("Sub Total", $pdfInvoice->items_total);
			$pdfInvoice->addTotal("VAT 10%", $pdfInvoice->GetPercentage(10));
			$pdfInvoice->addTotal("Discount 10%", $pdfInvoice->GetPercentage(10), "red", true);
			$pdfInvoice->addTotal("Shipment", "100");
			$pdfInvoice->addTotal("Grand Total", $pdfInvoice->GetGrandTotal());
			
			// Set badge
			$pdfInvoice->addBadge("مدفوعة");
			// Add title
			$pdfInvoice->addTitle("Important Notice");
			// Add Paragraph
			$pdfInvoice->addParagraph("No item will be replaced or refunded if you don't have the invoice with you. You can refund within 2 days of purchase.");
			$pdfInvoice->addParagraph("Special Charactors Allowed: ” ? ” | ” Data p?atno?ci ” åäö");
			// Set footer note
			$pdfInvoice->setFooternote("Buy this script from <a href='http://codecanyon.net/item/php-invoice-php-class-for-beautiful-pdf-invoices/9512525/'>codecanyon</a> ");
			return $pdfInvoice->render('invoice.pdf', 'D');

//			$invoice->sale = $invoice->sale()->withoutGlobalScope('draft')->first();
//			return view('accounting.printer.a4',compact('invoice'));
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
			openssl_sign($req, $signature, $privateKey);
			if($signature) {
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
			return view('accounting.printer.receipt', compact('invoice'));
		}
		
		
		public function voucher(Payment $voucher)
		{
			
			$payment = $voucher;
			return view('accounting.printer.voucher', compact('voucher', 'payment'));
		}
		
	}
