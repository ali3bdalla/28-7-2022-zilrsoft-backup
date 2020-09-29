<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\TransactionsContainer;
use App\Models\User;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    //
    /**
     * @return Factory|View
     */
    public function index()
    {
        $entities = TransactionsContainer::orderBy('created_at', 'desc')->paginate(40);
        return view('accounting.transactions.index', compact('entities'));
    }


    public function show(TransactionsContainer $entity)
    {
        return view('accounting_module.entities.show',compact('entity'));
    }




    public function showUserEntities(Account $account,User $user)
    {
        // return 1;
        if ($account->slug != 'vendors' && $account->slug == 'clients') {
            return back();
        }
        return view('accounting_module.entities.user', compact('user', 'account'));
    }



    public function showItemEntities(Account $account, Item $item ){

        $transactions = $account->transactions()->where('item_id',$item->id)->paginate(50);
        return view('accounting.charts.transactions.item',compact('item','transactions','account'));
    }

}
