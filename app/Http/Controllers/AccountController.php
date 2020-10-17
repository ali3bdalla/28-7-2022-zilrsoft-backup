<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $accounts = Account::where('parent_id', 0)->withCount('children')->get();
        return view('accounting.charts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:accounts,id|integer',
        ]);

        if (isset($request->parent_id)) {
            $parent_id = $request->parent_id;
        } else {
            $parent_id = 0;
        }
        $accounts = Account::all();
        return view('accounting.charts.create', compact('accounts', 'parent_id'));

    }

    /**
     * Display the specified resource.
     *
     * @param Account $account
     *
     * @param Request $request
     * @return Response
     */
    public function show(Account $account, Request $request)
    {
        if ($account->slug == 'stock') {
            return redirect(route('accounts.show.stock', $account->id));
        }


        if ($account->slug == 'vendors' || $account->slug == 'clients') {
            return redirect(route('accounts.show.users', $account->id));
        }


         return view('accounting.charts.transactions.v2.index', compact('account'));
    }

    public function showStock(Account $account)
    {
        if ($account->slug != 'stock') {
            return back();
        }
        $items = Item::all();
        return view('accounting.charts.transactions.items', compact('items', 'account'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param Account $account
     *
     * @return Response
     */
    public function edit(Account $account)
    {

        $ids = $account->children()->pluck('id')->toArray();
        $ids[] = $account->id;
        $accounts = Account::WhereNotIn('id', $ids)->get();
        return view('accounting.charts.edit', compact('account', 'accounts'));
        //
    }
	
	
	public function reports()
	{
		$accounts = Account::all();
		return view('accounting.charts.reports.index',compact('accounts'));
    }

}
