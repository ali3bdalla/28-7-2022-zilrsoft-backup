<?php

namespace Modules\Sales\Http\Controllers;

use App\Account;
use App\Invoice;
use App\Item;
use App\Manager;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Expenses\Jobs\createExpensesPurchaseJob;
use Modules\Sales\Http\Requests\StoreSalesRequest;

class CreateController extends Controller
{


    public function showCreateForm()
    {
        $salesmen = Manager::all();
        $clients = User::where('is_client', true)->get()->toArray();
        $expenses = Item::where('is_expense', true)->get();
        $gateways = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->get();

        return view('sales::create.index', compact('clients', 'salesmen', 'gateways', 'expenses'));
    }


    public function store(StoreSalesRequest $request)
    {

        DB::beginTransaction();
        try {
            $authUser = auth()->user();
            $invoice = Invoice::create([
                'invoice_type' => 'sale',
                'notes' => $request->has('notes') ? $request->input('notes') : "",
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'parent_invoice_id' => $request->input('parent_id') == null ? 0 : $request->input('parent_id'),
                'is_deleted' => $request->has('is_deleted') ? $request->input('is_deleted') : 0
            ]);
            $salesInvoice = $invoice->sale()->create([
                'salesman_id' => $authUser->id,
                'client_id' => $request->input('client_id'),
                'organization_id' => $authUser->organization_id,
                'invoice_type' => 'sale',
                'alice_name' => $request->input('alice_name'),
                "prefix" => "SAI-"
            ]);
            dispatch(new CreateExpensesPurchaseJob($invoice,$request->input('items')));


            DB::commit();

            return $salesInvoice;
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (ValidationException $validationException) {
            DB::rollBack();
            throw $validationException;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;

        }


    }

}
