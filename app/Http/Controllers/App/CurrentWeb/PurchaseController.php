<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;
use App\Scopes\DraftScope;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class PurchaseController extends Controller
{


    public function index()
    {
        $vendors = User::whereIsVendor(true)->get();
        $creators = Manager::all();
        $is_pending = false;
        return view('accounting.purchases.index', compact('vendors', 'creators', 'is_pending'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $receivers = Manager::all();
        $vendors = User::where([['is_vendor', true], ['is_system_user', false]])->get()->toArray();
        $expenses = Expense::all();
        $gateways = [];
        return view('accounting.purchases.create', compact('vendors', 'receivers', 'gateways', 'expenses'));
        //
    }


    /**
     * @param Invoice $purchase
     *
     * @return Factory|View
     */
    public function edit(Invoice $purchase)
    {

        $invoice = $purchase->load('manager','user','department','branch');
        $items = [];
        $data_source_items = $invoice->items()->with('item')->get();
        foreach ($data_source_items as $item) {
            if ($item->item->is_need_serial) {
                $item['serials'] = $item->item->serials()->purchase($invoice->id)->get();
            }
            $items [] = $item;
        }
        $gateways = [];


        return view('accounting.purchases.edit', compact( 'invoice', 'items', 'gateways'));
    }

    public function clone(Invoice $purchase)
    {
        $receivers = Manager::all();
        $vendors = User::where([['is_vendor', true], ['is_system_user', false]])->get()->toArray();
        $expenses = Expense::all();
        $gateways = [];

        $cloned_items = [];
        foreach ($purchase->items()->with('item')->get() as $item) {
            if ($item->item->is_need_serial) {
                $item->serials = $item->item->serials()->withoutGlobalScope(DraftScope::class)
                    ->where([["purchase_id", $purchase->id]])
                    ->pluck('serial');
            } else {
                $item->serials = [];
            }
            $cloned_items[] = $item;
        }
        return view('accounting.purchases.clone', compact('vendors', 'receivers', 'gateways', 'expenses', 'purchase', 'cloned_items'));
        //
    }


    /**
     * Display the specified resource.
     *
     * @param Invoice $purchase
     *
     * @return Application|Factory|View
     */
    public function show(Invoice $purchase)
    {
        $transactions = $purchase->transactions()->with('account')->get();
        return view(
            'accounting.purchases.show', [
                'invoice' => $purchase->load(['items.item','user','manager']), 'transactions' => $transactions
            ]
        );
    }


    public function drafts()
    {
        $vendors = User::whereIsVendor(true)->get();
        $creators = Manager::all();
        $is_pending = true;
        return view('accounting.purchases.index', compact('vendors', 'creators', 'is_pending'));
    }

}
