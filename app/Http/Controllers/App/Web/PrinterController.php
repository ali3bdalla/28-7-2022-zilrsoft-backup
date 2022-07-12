<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Voucher;
use App\Repository\InvoiceRepositoryContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrinterController extends Controller
{
    private InvoiceRepositoryContract $invoiceRepositoryContract;

    public function __construct(InvoiceRepositoryContract $invoiceRepositoryContract)
    {
        $this->invoiceRepositoryContract = $invoiceRepositoryContract;
    }

    public function show_public_invoice($invoicePublicIdElementsHash)
    {
        $invoice = Invoice::getInvoiceByPublicIdHash($invoicePublicIdElementsHash);

        return view('accounting.printer.a4', compact('invoice'));
    }


    public function print_a4(Invoice $invoice)
    {

        return view('accounting.printer.a4', compact('invoice'));
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    public function sign_receipt_printer(Request $request): string
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
        return "";
    }

    /**
     * @return Factory|View
     */
    public function printers()
    {
        return view('accounting.printer.printers');
    }

    public function print_receipt(Invoice $invoice)
    {
        $invoice = $this->invoiceRepositoryContract->getInvoiceFullDetails($invoice);
        return view('accounting.printer.receipt', compact('invoice'));
    }

    public function voucher(Voucher $voucher)
    {
        $payment = $voucher;

        return view('accounting.printer.voucher', compact('voucher', 'payment'));
    }
}
