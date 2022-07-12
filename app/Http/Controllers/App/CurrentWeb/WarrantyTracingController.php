<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Enums\InvoiceItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Jobs\QuickBooks\RefundSalesQuickBooksSyncJob;
use App\Jobs\QuickBooks\SalesQuickBooksSyncJob;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Order;
use App\Models\User;
use App\Repository\AccountRepositoryContract;
use http\Env\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Enum\Laravel\Rules\EnumRule;

class WarrantyTracingController extends Controller
{
    private AccountRepositoryContract $accountRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract)
    {
        $this->accountRepositoryContract = $accountRepositoryContract;
    }


    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('warranty_tracing.index');
    }

    /**
     * Show the specified resource.
     * @param Invoice $sale
     * @return Application|Factory|View
     */
    public function show(Invoice $warrantyTracing)
    {
        $transactions = $warrantyTracing->transactions()->with('account')->get();
        $invoice = $warrantyTracing->load('warrantyTracingHistories.creator', 'items.item');
        $statuses = InvoiceItemStatusEnum::labels();
        return view('warranty_tracing.view', compact('invoice', 'transactions','statuses'));
    }

    public function update(Invoice $warrantyTracing,\Illuminate\Http\Request $request)
    {
        $request->validate([
            'status' => ['required','string',new EnumRule(InvoiceItemStatusEnum::class)]
        ]);
        $warrantyTracing->update([
            'status' => $request->input('status')
        ]);
        $warrantyTracing->items()->update([
            'status' => $request->input('status')
        ]);
        $warrantyTracing->warrantyTracingHistories()->create([
            'creator_id'  => Auth::id(),
            'status' =>  $request->input('status')
        ]);
        return back();
    }
}
