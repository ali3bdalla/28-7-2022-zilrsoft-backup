<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Enums\InvoiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Repository\Eloquent\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {

        $this->invoiceRepository = $invoiceRepository;
    }

    public function index()
    {
        $workingYears = Auth::user()->organization->working_years;
        $activeYear = Auth::user()->active_year;
        $quickBooksInvoices = $this->invoiceRepository->getQuickBooksTodaySales(Auth::user());
        $todayDate = Carbon::now()->toDateString();
        $localInvoices = Invoice::where('invoice_type',InvoiceTypeEnum::sale())->whereDate('created_at',$todayDate)->pluck('net');
        return view('home.index', compact('workingYears', 'todayDate','activeYear','quickBooksInvoices','localInvoices'));
    }

    public function changeSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'active_year' => ['nullable', 'integer', Rule::in(Auth::user()->organization->working_years)]
        ]);
        Auth::user()->update([
            'active_year' => $request->input('active_year')
        ]);
        return back()->with([]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return back();
    }
}

