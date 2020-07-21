<?php

namespace Modules\Accounting\Http\Controllers;

use App\Account;
use App\Http\Requests\Accounting\Transaction\CreateTransactionRequest;
use App\Http\Requests\Accounting\Transaction\DeleteTransactionsRequest;
use App\Transaction;
use App\TransactionsContainer;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class EntityController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $transactionsContainers = TransactionsContainer::
            with('entities')
            ->orderBy('created_at', 'desc')
            ->paginate(40);
        return view('accounting::entities.index', compact('transactionsContainers'));
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
        $transactions = TransactionsContainer::where('id', $transaction->id)->paginate(10);
        return view('accounting.transactions.index', compact('transactions'));
    }


    public function destroy(Transaction $transaction, DeleteTransactionsRequest $request)
    {
        return $request->erase($transaction->container);
    }

}
