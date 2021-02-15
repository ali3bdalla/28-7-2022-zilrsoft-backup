<?php

namespace App\Jobs\Order;

use AliAbdalla\PDF\APDFCore;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Organization;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
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
	    $view =  view('whatsapp.invoice_pdf',[
	        'order' => $this->order,
            'invoice' => $this->invoice
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'XB'
        ]);
        $mpdf->SetDirectionality('rtl');

        $mpdf->WriteHTML($view);

        $mpdf->SetHTMLFooter('
<table width="100%">
    <tr>
        <td width="100%" align="center" style="font-weight: bold;">المملكة العربية السعودية - القصيم - الرس - طريق الملك فهد - غرب الاحوال المدنية</td>
    </tr>
    <tr>
        <td width="100%" align="center" style="font-weight: bold;">'.url('').'</td>
    </tr>
</table>');
        $fileName = 'order_' . $this->order->id . '_' . Carbon::now()->toDateString() . '.pdf';
        $path = storage_path('app/public/orders/' . $fileName);
		$mpdf->Output();
        $mpdf->Output( $path,'F');
        $this->order->update([
            'pdf_path' => 'orders/' . $fileName
        ]);
	}
}
