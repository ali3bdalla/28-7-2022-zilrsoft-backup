<?php

namespace App\Jobs\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class CreateOrderPdfSnapshotJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var Invoice
     */
    private $invoice;
    private $client;
    private $order;

    /**
     * Create a new event instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice->fresh();
        $this->order = Order::where('invoice_id', $invoice->id)->first();
        $this->client = User::find($invoice->user_id);
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        $view = view('whatsapp.invoice_pdf', [
            'order' => $this->order,
            'invoice' => $this->invoice,
        ]);
        $mpdf = new Mpdf([
            'default_font' => 'XB',
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
        $fileName = 'order_'.Carbon::now()->toDateString().'_'.$this->order->id.'.pdf';
        $path = storage_path('app/public/orders/'.$fileName);
        $mpdf->Output($path, 'F');
        $path = 'orders/'.$fileName;
        $this->order->update([
            'pdf_path' => $path,
        ]);

        return Storage::url($path);
    }
}
