<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = Account::where('parent_id', 0)->withCount('children')->get();
        return view('accounting.charts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $request->validate(
            [
                'parent_id' => 'nullable|integer',
                // |organization_exists:App\Models\Account,id
            ]
        );

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
     * @return Application|Factory|View
     */
    public function show(Account $account, Request $request)
    {
        if ($account->slug == 'stock' && $request->has('show_items') && $request->input('show_items') === 1) {
            $items = Item::paginate(50);
            return view('accounting.charts.transactions.items', compact('items', 'account'));
        }


        if ($account->slug == 'vendors') {
            $identities = User::where(
                [
                    ['is_vendor', true],
                    ['is_system_user', false]
                ]
            )->get();
            return view('accounting_module.entities.identities', compact('identities', 'account'));
        }

        if ($account->slug == 'clients') {
            $identities = User::where(
                [
                    ['is_client', true],
                    ['is_system_user', false]
                ]
            )->get();
            return view('accounting_module.entities.identities', compact('identities', 'account'));
        }


        $user = null;
        $item = null;
        return view('accounting.charts.transactions.v2.index', compact('account', 'item', 'user'));
    }


    public function showItem(Account $account, Item $item)
    {
        $user = null;
        return view('accounting.charts.transactions.v2.index', compact('account', 'item', 'user'));
    }


    public function showIdentity(Account $account, User $identity)
    {
        $user = $identity;
        $item = null;
        return view('accounting.charts.transactions.v2.index', compact('account', 'item', 'user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Account $account
     *
     * @return Application|Factory|View
     */
    public function edit(Account $account)
    {

        $ids = $account->children()->pluck('id')->toArray();
        $ids[] = $account->id;
        $accounts = Account::WhereNotIn('id', $ids)->get();
        return view('accounting.charts.edit', compact('account', 'accounts'));
    }


    public function reports()
    {
        $accounts = Account::all();
        return view('accounting.charts.reports.index', compact('accounts'));
    }

}
