<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Order;
use App\Models\User;
use App\Scopes\DraftScope;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class SaleController extends Controller
{
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
        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();

        return view('sales.create', compact('clients', 'salesmen', 'gateways', 'expenses'));
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
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
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
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
        return view('sales.create_draft_service', compact('clients', 'salesmen', 'gateways', 'services'));
    }

    function clone(Invoice $sale)
    {

        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
        $sale = $sale->load('items.item.items.item', 'items.item.data', 'sale.client', 'sale.salesman');
        return view('sales.clone_draft', compact('clients', 'salesmen', 'gateways', 'expenses', 'sale'));
    }


    function toInvoice(Invoice $sale)
    {
        $sale->sale = $sale->sale()->withoutGlobalScope(DraftScope::class)->first();
        $salesmen = Manager::all();
        $clients = User::whereIsClient(true)->orderBy('id', 'asc')->get()->toArray();
        $expenses = Item::whereIsExpense(true)->get();
        $gateways = Account::whereSlug("temp_reseller_account")->whereIsSystemAccount(true)->get();
        $sale = $sale->load('items.item.items.item', 'items.item.data', 'sale.client', 'sale.salesman');
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
        $invoice->sale = $invoice->sale()->with('client', 'salesman')->withoutGlobalScope(DraftScope::class)->first();
        return view('sales.view', compact('invoice', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Invoice $sale
     * @return Application|Factory|View
     */
    public function edit(Invoice $sale)
    {
        $invoice = $sale;
        $sale = $invoice->sale;
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
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
        return view('sales.create_return', compact('sale', 'invoice', 'items', 'gateways', 'expenses'));
    }

}
