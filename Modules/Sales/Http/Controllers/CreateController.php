<?php

namespace Modules\Sales\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Expenses\Jobs\createExpensesPurchaseJob;
use Modules\Sales\Http\Requests\CreateSalesRequest;

class CreateController extends Controller
{


    public function showCreateForm()
    {
        $salesmen = Manager::all();
        $clients = User::where('is_client', true)->get()->toArray();
        $expenses = Item::where('is_expense', true)->get();
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();
//        return auth()->user()->load('department','branch','user');
        return view('sales::create.index', compact('clients', 'salesmen', 'gateways', 'expenses'));
    }


    public function store(CreateSalesRequest $request)
    {
        return $request->store();
    }

}
