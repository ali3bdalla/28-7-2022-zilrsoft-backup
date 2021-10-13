<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Item;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class EntityController extends Controller
{
    /**
     * @return Factory|View|Application|View
     */
    public function index()
    {
        $entities = Entry::orderBy('created_at', 'desc')->paginate(15);
        return view('accounting.transactions.index', compact('entities'));
    }


    public function show(Entry $entity)
    {

        return view('accounting_module.entities.show', compact('entity'));
    }


    public function create()
    {
        $accounts = Account::get();
        $items = [];
        $clients = User::where(
            [
                ['is_client', true],
            ]
        )->get();
        $vendors = User::where(
            [
                ['is_vendor', true],
            ]
        )->get();
        return view('accounting.transactions.create', compact('accounts', 'items', 'vendors', 'clients'));
    }

    public function showUserEntities(Account $account, User $user)
    {

        if ($account->slug != 'vendors' && $account->slug != 'clients') {
            return back();
        }
        return view('accounting_module.entities.user', compact('user', 'account'));
    }


    public function showItemEntities(Account $account, Item $item)
    {

        $transactions = $account->transactions()->where('item_id', $item->id)->paginate(50);
        return view('accounting.charts.transactions.item', compact('item', 'transactions', 'account'));
    }
}
