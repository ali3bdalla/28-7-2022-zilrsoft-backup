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


		$organization = $this->invoice->organization;

		$invoice = $this->invoice;
		$pdfInvoice = new APDFCore("decentblue", ' ');
		$pdfInvoice->setLogo(asset($organization->logo));
		$pdfInvoice->setType(__("store.order.sales_invoice"));
		$pdfInvoice->setDirection('rtl');
		$pdfInvoice->setLang('ar');
		$pdfInvoice->setReference($invoice->invoice_number);
		$pdfInvoice->setDate(Carbon::parse($invoice->created_at)->toDateTimeString());
		$pdfInvoice->setFrom(
			[
				['key' => false, 'value' => $organization->title_ar],
				['key' => __("store.order.vatId"), 'value' => $organization->vat],
				['key' => __("store.order.vatId"), 'value' => $organization->cr],
				['key' => __("store.order.phone"), 'value' => $organization->phone_number],
				['key' => __("store.order.branch"), 'value' => __("store.order.online_sales")],
				['key' => false, 'value' => $organization->description_ar],
			]
		);
		$pdfInvoice->setTo(array($invoice->user->name_ar, $invoice->user->phone_number, __("store.order.shipping_address")));

		foreach ($invoice->items as $item) {
			$pdfInvoice->addItem($item->item->locale_name, $item->qty, $item->price, $item->total, $item->tax, $item->net, $item->getInvoiceItemSerials()->pluck('serial')->toArray());
		}
		$pdfInvoice->addTotal(__("store.order.total"), $invoice->total);
		$pdfInvoice->addTotal(__("store.order.tax"), $invoice->tax);
		$pdfInvoice->addTotal(__("store.order.shipping"), 0);
		$pdfInvoice->addTotal(__("store.order.net"), $invoice->net);
		$pdfInvoice->addBadge(__("store.order.draft"));
		$pdfInvoice->setThanksMessage(__("store.order.happy_to_serv_you"));
		$pdfInvoice->addTitle(__("store.order.terms_privacy"));
		foreach (__("store.order.terms_list") as $key => $value) {
			$pdfInvoice->addParagraph($value);
		}
		$pdfInvoice->setFooterContent($organization->country->ar_name . ' - ' . __("store.order.our_address_state") . ' - ' . $organization->city_ar . " - " . $organization->address_ar);
		try {
			$fileName = 'order_' . $this->order->id . '_' . Carbon::now()->toDateString() . '.pdf';
			$path = 'orders/' . $fileName;
			Storage::put($path, $pdfInvoice->render($fileName, Destination::STRING_RETURN), 'public');
			$this->order->update([
				'pdf_path' => $path,
				'lang' => app()->getLocale()
			]);
			return $path;
		} catch (Exception $exception) {
			throw $exception;
		}
	}
}
