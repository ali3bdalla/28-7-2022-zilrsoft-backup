<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Scopes\DraftScope;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrinterController extends Controller
{
    public function show_public_invoice($invoicePublicIdElementsHash)
    {
        $invoice = Invoice::getInvoiceByPublicIdHash($invoicePublicIdElementsHash);

        $invoice = $this->get_invoice_dependencies($invoice);

        return view('accounting.printer.a4', compact('invoice'));
    }

    private function get_invoice_dependencies(Invoice $invoice): Invoice
    {
        $invoice->sale = $invoice->sale()->withoutGlobalScope(DraftScope::class)->first();

        return $invoice;
    }

    public function print_a4(Invoice $invoice)
    {
        $invoice = $this->get_invoice_dependencies($invoice);

        return view('accounting.printer.a4', compact('invoice'));
    }

    /**
     * @return string
     */
    public function sign_receipt_printer(Request $request)
    {
        $KEY = public_path('accounting/key/private-key.pem');
        $req = $request->input('request');
        $privateKey = openssl_get_privatekey(file_get_contents($KEY));
        $signature = null;
        openssl_sign($req, $signature, $privateKey);
        if ($signature) {
            header('Content-type: text/plain');

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
