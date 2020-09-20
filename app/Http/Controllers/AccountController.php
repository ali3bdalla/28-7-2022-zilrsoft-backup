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
     * @return Response
     */
    public function show(Account $account, Request $request)
    {
        if ($account->slug == 'stock') {
            return redirect(route('accounts.show.stock', $account->id));
        }

        // if ($account->slug == 'clients') {
        //     $users = User::where('is_client', true)->paginate(50);
        //     return view('accounting.charts.transactions.identity', compact('users', 'account'));
        // } else if ($account->slug == 'vendors') {
        //     $users = User::where('is_vendor', true)->paginate(50);
        //     return view('accounting.charts.transactions.identity', compact('users', 'account'));
        // } else if ($account->slug == 'stock') {
        //     $items = $this->get_account_stock_item_transactions();
        //     $items = $items['items'];
        //     return view('accounting.charts.transactions.items', compact('items', 'account'));

        // }

        // return view('accounting.charts.transactions.v2.index', compact('account'));
    }

    public function showStock(Account $account)
    {
        if ($account->slug != 'stock') {
            return back();
        }
        $items = Item::all();
        return view('accounting.charts.transactions.items', compact('items', 'account'));
    }



    public function showVendors(Account $account)
    {
        if ($account->slug != 'vendors') {
            return back();
        }
        return view('accounting.charts.transactions.items', compact('items', 'account'));
    }


    public function showClients(Account $account)
    {
        if ($account->slug != 'clients') {
            return back();
        }
        return view('accounting.charts.transactions.items', compact('items', 'account'));
    }



    public function showItem(Account $account, Item $item ){

        $transactions = $account->transactions()->where('item_id',$item->id)->paginate(50);
        return view('accounting.charts.transactions.item',compact('item','transactions','account'));
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
     

}
