<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;

class SaleController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // auth()->loginUsingId(1);
        $clients = User::where('is_client', true)->get();
        $creators = Manager::all();
        $departments = Department::all();
        return view('sales.index', compact('clients', 'creators', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $salesmen = Manager::all();
        $clients = User::where('is_client', true)->get()->toArray();
        $expenses = Item::where('is_expense', true)->get();
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
        return view('sales.create', compact('clients', 'salesmen', 'gateways', 'expenses'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Invoice $saleInvoice)
    {
        return view('sales.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Invoice $saleInvoice)
    {
        return view('sales::edit');
    }

}
