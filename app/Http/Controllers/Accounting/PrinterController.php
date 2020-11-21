<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use AliAbdalla\PDF\APDFCore;
	use App\Http\Controllers\Controller;
	use App\Models\Invoice;
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
			$pdfInvoice = new APDFCore("decentblue", ' ');
//			$pdfInvoice->setLogo(auth()->user()->organization->logo);
			$pdfInvoice->setType("فاتورة مبيعات");
			$pdfInvoice->setDirection('rtl');
			$pdfInvoice->setLang('ar');
			$pdfInvoice->setReference($invoice->invoice_number);
			$pdfInvoice->setDate($invoice->created_at);
//			, , auth()->user()->organization->cr,auth()->user()->organization->phone_number, 'مبيعات الاونلاين',auth()->user()->organization->description_ar
			$pdfInvoice->setFrom(
				[['key' => false, 'value' => auth()->user()->organization->title_ar],
					['key' => 'الرقم الضريبي', 'value' => auth()->user()->organization->vat],
					['key' => 'السجل التجاري', 'value' => auth()->user()->organization->cr],
					['key' => "الهاتف", 'value' => auth()->user()->organization->phone_number],
					['key' => "الفرع", 'value' => 'مبيعات الاونلاين'],
					['key' => false, 'value' => auth()->user()->organization->description_ar],
				]
			);
			$pdfInvoice->setTo(array($invoice->user->name_ar, $invoice->user->phone_number, "عنوان الشحن"));
			
			foreach($invoice->items as $item) {
				$pdfInvoice->addItem($item->item->locale_name, $item->qty, $item->price, $item->total, $item->tax, $item->net, $item->getInvoiceItemSerials()->pluck('serial')->toArray());
			}
			
			$pdfInvoice->addTotal("المجموع", $invoice->total);
			$pdfInvoice->addTotal("الضريبة", $invoice->tax);
			$pdfInvoice->addTotal("الشحن", 0);
			$pdfInvoice->addTotal("النهائي", $invoice->net);
			$pdfInvoice->addBadge("مسددة");
			$pdfInvoice->setThanksMessage("سعدنا بخدمتك");
			$pdfInvoice->addTitle("الشروط والاحكام");
			$pdfInvoice->addParagraph("* البضاعة المباعة لاترد ولا تستبدل بعد فتحها .");
			$pdfInvoice->addParagraph("* الارجاع خلال ثلاثة أيام .");
			$pdfInvoice->addParagraph("* التبديل خلال سبعة أيام .");
//			$pdfInvoice->setFooternote("");
			$pdfInvoice->setFooterContent(auth()->user()->organization->country->ar_name . ' - القصيم - ' . auth()->user()->organization->city_ar . " - " . auth()->user()->organization->address_ar);
//
			$pdfInvoice->bodyBackgroundImage = "https://zilrsoft-cdn.fra1.digitaloceanspaces.com/organizations/1/dddddd.jpg";
			return  $pdfInvoice->render('invoice.pdf', 'I');
//
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
