<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Voucher\DatatableRequest;
use App\Models\Account;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VoucherController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $identities = User::all();
        $creators = Manager::all();
        return view('accounting.vouchers.index', compact('creators', 'identities'));
    }

    /**
     * @param DatatableRequest $request
     *
     * @return mixed
     */
    public function datatable(DatatableRequest $request)
    {
        return $request->data();
    }

    /**
     * @param Payment $voucher
     * @return Factory|View
     */
    public function show(Payment $voucher)
    {
        $payment = $voucher;
        return view('accounting.vouchers.show', compact('payment'));
    }

    public function create(Request $request)
    {
        $accounts = auth()->user()->gateways()->get();
        $voucher_types = config('global.voucher_types');
        if ($request->input('voucher_type') == 'receipt') {
            $voucher_type = 'receipt';
            $users = User::where(
                [
                    ['is_client', true],
                    ['is_system_user', false],
                ]
            )->with('gateways.bank')->get();
        } else {
            $voucher_type = 'payment';
            $users = User::where(
                [
                    ['is_vendor', true],
                    ['is_system_user', false],
                ]
            )->with('gateways.bank')->get();
        }

        return view('accounting.vouchers.create', compact('accounts', 'users', 'voucher_types', 'voucher_type'));
    }


    public function createSupplierVoucher(Request $request)
    {

        $accounts = auth()->user()->gateways()->get();

        $expenseMainAccount = Account::findOrFail(75);

        $expensesAccounts = Account::whereIn('id', $expenseMainAccount->getChildrenIncludeMe())->get();
        return view('vouchers.create_supplier', compact('accounts', 'expensesAccounts'));

    }

}
