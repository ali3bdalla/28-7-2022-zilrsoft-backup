<?php

namespace App\Http\Controllers\App\CurrentWeb;

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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SaleController extends Controller
{
    private AccountRepositoryContract $accountRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract)
    {
        $this->accountRepositoryContract = $accountRepositoryContract;
    }

    public function uploadToQuickbooks(Invoice $sale)
    {
        if ($sale->quickbook_id || $sale->is_draft) {
            return back();
        }
        if ($sale->invoice_type == 'sale')
            dispatch_sync(new SalesQuickBooksSyncJob($sale, Auth::user()));
        elseif ($sale->invoice_type == 'return_sale')
            dispatch_sync(new RefundSalesQuickBooksSyncJob($sale, Auth::user()));
        return back();
    }

    public function report()
    {
        return view('sales.report');
    }
    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function index()
    {
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $creators = Manager::get();
        $departments = Department::get();
        return view('sales.index', compact('clients', 'creators', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View
     */
    public function create()
    {
        $departments = Department::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        return view('sales.create', compact('clients', 'departments', 'gateways', 'expenses'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View
     */
    public function drafts()
    {
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $creators = Manager::all();
        return view('sales.drafts', compact('clients', 'creators'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View
     */
    public function createDraft()
    {
        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        return view('sales.create_draft', compact('clients', 'salesmen', 'gateways', 'expenses'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View
     */
    public function createServiceDraft()
    {
        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $services = Item::whereIsService(true)->get();
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        return view('sales.create_draft_service', compact('clients', 'salesmen', 'gateways', 'services'));
    }

    function clone(Invoice $sale)
    {

        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        $sale = $sale->load('items.item.items.item', 'items.item.data');
        return view('sales.clone_draft', compact('clients', 'salesmen', 'gateways', 'expenses', 'sale'));
    }


    function toInvoice(Invoice $sale)
    {
        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get()->toArray();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        $sale = $sale->load('items.item.items.item', 'items.item.data');
        $isOrder = Order::where([['draft_id', $sale->id], ['status', 'in_progress']])->count() == 1;
        return view('sales.clone', compact('clients', 'salesmen', 'gateways', 'expenses', 'sale', 'isOrder'));
    }

    /**
     * Show the specified resource.
     * @param Invoice $sale
     * @return Application|Factory|View
     */
    public function show(Invoice $sale)
    {
        $transactions = $sale->transactions()->with('account')->get();
        $invoice = $sale->load('payments.account', 'items.item');
        return view('sales.view', compact('invoice', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Invoice $sale
     * @return Application|Factory|View
     */
    public function edit(Invoice $sale)
    {
        $invoice = $sale->load("department", "branch", "user", "manager");
        $items = [];
        $data_source_items = $invoice->items()->where('parent_kit_id', 0)->with('item')->get();
        foreach ($data_source_items as $item) {
            if ($item->item->is_need_serial) {
                $item['serials'] = $item->item->serials()->sale($invoice->id)->get();
            }
            if ($item->item->is_kit) {
                $item['items'] = $invoice->items()->kitItems($item->id)->with('item')->get();
            }
            $items[] = $item;
        }
        $expenses = [];
        $gateways = $this->accountRepositoryContract->getPaymentMethodsAccountsListToAuthedManager();
        return view('sales.create_return', compact('invoice', 'items', 'gateways', 'expenses'));
    }
}
