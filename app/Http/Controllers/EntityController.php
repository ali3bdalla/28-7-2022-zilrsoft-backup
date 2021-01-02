<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{
	//
	/**
	 * @return Factory|View
	 */
	public function index()
	{
		$entities = TransactionsContainer::orderBy('created_at', 'desc')->paginate(15);
		return view('accounting.transactions.index', compact('entities'));
	}


	public function show(TransactionsContainer $entity)
	{

		return view('accounting_module.entities.show', compact('entity'));
	}


	public function create()
	{
		$accounts = Account::get(); //withCount('children')->having('children_count', 0)->
		$items = [];
		$clients = User::where(
			[
				['is_client', true],
				// ['is_system_user', false],
			]
		)->get();
		$vendors = User::where(
			[
				['is_vendor', true],
				// ['is_system_user', false],
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
