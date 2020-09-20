<?php

namespace App\Http\Controllers;

use App\Models\TransactionsContainer;
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
}
