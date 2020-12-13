<?php
	
	namespace App\Jobs\Order;
	
	use AliAbdalla\PDF\APDFCore;
	use App\Models\Invoice;
	use App\Models\Order;
	use App\Models\Organization;
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
			$this->invoice = $invoice->fresh();
			$this->order = Order::where('draft_id', $invoice->id)->first();
			$this->client = User::find($invoice->user_id);
		}
		
		/**
		 * Execute the job.
		 *
		 * @return string
		 */
		public function handle()
		{
			
			
			$organization =$this->invoice->organization;

			$invoice = $this->invoice;
			$pdfInvoice = new APDFCore("decentblue", ' ');
//			$pdfInvoice->setLogo($organization->logo);
			$pdfInvoice->setType("فاتورة مبيعات");
			$pdfInvoice->setDirection('rtl');
			$pdfInvoice->setLang('ar');
			$pdfInvoice->setReference($invoice->invoice_number);
			$pdfInvoice->setDate(Carbon::parse($invoice->created_at)->toDateTimeString());
			$pdfInvoice->setFrom(
				[['key' => false, 'value' => $organization->title_ar],
					['key' => 'الرقم الضريبي', 'value' => $organization->vat],
					['key' => 'السجل التجاري', 'value' => $organization->cr],
					['key' => "الهاتف", 'value' => $organization->phone_number],
					['key' => "الفرع", 'value' => 'مبيعات الاونلاين'],
					['key' => false, 'value' => $organization->description_ar],
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
			$pdfInvoice->setFooterContent($organization->country->ar_name . ' - القصيم - ' . $organization->city_ar . " - " . $organization->address_ar);
			try {
				$fileName = 'order_' . $this->order->id . '_' . Carbon::now()->toDateString() . '.pdf';
				$path = 'orders/' . $fileName;
				Storage::put($path, $pdfInvoice->render($fileName, Destination::STRING_RETURN), 'public');
				return $path;
			} catch(Exception $exception) {
				throw $exception;
			}
		}
	}
