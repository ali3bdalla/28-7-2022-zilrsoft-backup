<?php

namespace App\Http\Controllers\App\API;

use App\Models\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Transaction\CreateTransactionRequest;
use App\Http\Requests\Accounting\Transaction\DeleteTransactionsRequest;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TransactionsController extends Controller
{

    /**
     * @return Factory|View
     */
    public function index()
    {
        $transactions = TransactionsContainer::orderBy('created_at', 'desc')->paginate(40);
        return view('accounting.transactions.index', compact('transactions'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $accounts = Account::all();
        $items = [];
        $clients = User::where([
            ['is_client', true],
            ['is_system_user', false],
        ])->get();
        $vendors = User::where([
            ['is_vendor', true],
            ['is_system_user', false],
        ])->get();
        return view('accounting.transactions.create', compact('accounts', 'items', 'vendors', 'clients'));
    }

    /**
     * @param CreateTransactionRequest $request
     *
     * @return Application|ResponseFactory|Response
     */
    public function store(CreateTransactionRequest $request)
    {
        return $request->save();

    }

    /**
     * @param TransactionsContainer $transaction
     *
     * @return Factory|View
     */
    public function show(TransactionsContainer $transaction)
    {
        $transactions = TransactionsContainer::where('id', $transaction->id)->first();

        return $transactions->transactions()->with('account')->get();
        return view('accounting.transactions.index', compact('transactions'));
    }


    public function destroy(Transaction $transaction,DeleteTransactionsRequest $request)
    {
        return $request->erase($transaction->container);
    }

}