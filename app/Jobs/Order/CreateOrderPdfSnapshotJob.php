<?php
	
	namespace App\Jobs\Order;
	
	use AliAbdalla\PDF\APDFCore;
	use App\Models\Invoice;
	use App\Models\Order;
	use App\Models\User;
	use Carbon\Carbon;
	use Exception;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Facades\Storage;
	use Mpdf\Output\Destination;
	
	class CreateOrderPdfSnapshotJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		/**
		 * @var Invoice
		 */
		private $invoice;
		private $client;
		private $order;
		
		/**
		 * Create a new event instance.
		 *
		 * @param Invoice $invoice
		 */
		public function __construct(Invoice $invoice)
		{
			//
			$this->invoice = $invoice;
			$this->order = Order::where('draft_id', $invoice->id)->first();
			$this->client = User::find($invoice->user_id);
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
//			ob_end_clean();

//			$pdf = App::make('dompdf.wrapper');
//			$invoice = $this->invoice;
//			$invoice->sale = $this->invoice->sale()->withoutGlobalScope('draft')->first();
//
			$invoice = new APDFCore("decentblue");
//		$invoice->setLogo("../templates/basic/logo.jpg");
			$invoice->setType("Sales Invoice");
			$invoice->setReference("INV-55033645");
			$invoice->setDate(date('M dS ,Y', time()));
			$invoice->setDue(date('M dS ,Y', strtotime('+3 months')));
			$invoice->setFrom(array("Vendeur Nom", "Citroën", "128 AA Juanita Ave", "Île-de-France , DE 91740", "France"));
			$invoice->setTo(array("Nom de l'acheteur", "Sanofi-Synthélabo", "128 AA Juanita Ave", "Île-de-France , DE 91740", "France"));
			
			// Adding Items in table
			for($i = 0; $i < 10; $i ++) {
				$invoice->addItem("AMD Athlon X2DC-7450", "2.4GHz/1GB/160GB/SMP-DVD/VB", 1, "50%", 100, "50%");
				$invoice->addItem("PDC-E5300", "2.6GHz/1GB/320GB/SMP-DVD/FDD/VB", 1, 50, 100, 50);
				$invoice->addItem('LG 18.5" WLCD', "Test multilingue soutenu dans cette section en ajoutant personnalisée description du produit ici", 1, 0, 100, 0);
				$invoice->addItem("HP LaserJet (Citroën) źłóźśćąę ", "Ceci est une description de test pour le produit HP LaserJet 5200", 1, 0, 100, 20);
				
			}
			
			$invoice->addItem("AMD Athlon X2DC-7450", "2.4GHz/1GB/160GB/SMP-DVD/VB", 1, "50%", 100, "50%");
			$invoice->addItem("PDC-E5300", "2.6GHz/1GB/320GB/SMP-DVD/FDD/VB", 1, 50, 100, 50);
			$invoice->addItem('LG 18.5" WLCD', "Test multilingue soutenu dans cette section en ajoutant personnalisée description du produit ici", 1, 0, 100, 0);
			$invoice->addItem("HP LaserJet (Citroën) źłóźśćąę ", "Ceci est une description de test pour le produit HP LaserJet 5200", 1, 0, 100, 20);
			
			// Make sure to add  "$invoice->items_total" first before adding other "addTotal()"
			$invoice->addTotal("Sub Total", $invoice->items_total);
			$invoice->addTotal("VAT 10%", $invoice->GetPercentage(10));
			$invoice->addTotal("Discount 10%", $invoice->GetPercentage(10), "red", true);
			$invoice->addTotal("Shipment", "100");
			$invoice->addTotal("Grand Total", $invoice->GetGrandTotal());
			
			// Set badge
			$invoice->addBadge("مدفوعة");
			// Add title
			$invoice->addTitle("Important Notice");
			// Add Paragraph
			$invoice->addParagraph("No item will be replaced or refunded if you don't have the invoice with you. You can refund within 2 days of purchase.");
			$invoice->addParagraph("Special Charactors Allowed: ” ? ” | ” Data p?atno?ci ” åäö");
			// Set footer note
			$invoice->setFooternote("Buy this script from <a href='http://codecanyon.net/item/php-invoice-php-class-for-beautiful-pdf-invoices/9512525/'>codecanyon</a> ");
			
			try {
				$fileName = 'order_' . $this->order->id . '_' . Carbon::now()->toDateString() . '.pdf';
				$path = 'orders/' . $fileName;
				Storage::put($path, $invoice->render($fileName, Destination::STRING_RETURN), 'public');
				return $path;
			} catch(Exception $exception) {
				throw $exception;
			}
		}
	}
