<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Identity\CreateIdentityRequest;
use App\Http\Requests\Accounting\Identity\DatatableRequest;
use App\Http\Requests\Accounting\Identity\UpdateIdentityRequest;
use App\Models\Account;
use App\Models\Bank;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class IdentitiesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:create identity|edit identity|view identity|delete identity']);
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $this->middleware("permission:view identity");


        return view('accounting.identities.index');
    }

    /**
     * @param DatatableRequest $request
     *
     * @return LengthAwarePaginator
     */
    public function datatable(DatatableRequest $request)
    {
        return $request->data();
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $this->middleware(['permission:create identity']);
        $banks = Bank::all();
        return view('accounting.identities.create', compact('banks'));
    }

    /**
     * @param User $identity
     *
     * @return Factory|View
     */
    public function show(User $identity)
    {

        $this->middleware(['permission:view identity']);
        $clientAccount = Account::where('slug', 'clients')->first();
        $vendorAccount = Account::where('slug', 'vendors')->first();
        return view('accounting.identities.view', compact('identity', 'clientAccount', 'vendorAccount'));
    }

    /**
     * @param CreateIdentityRequest $request
     *
     * @return mixed
     * @throws Exception
     */
    public function store(CreateIdentityRequest $request)
    {
        return $request->save();
    }

    public function edit(User $identity)
    {
        $this->middleware(['permission:edit identity']);
        $banks = Bank::all();

        return view('accounting.identities.edit', compact('identity', 'banks'));
    }

    /**
     * @throws Exception
     */
    public function update(UpdateIdentityRequest $request, User $identity)
    {
        return $request->save($identity);
    }

    /**
     * @param User $identity
     */
    public function destroy(User $identity)
    {
        $this->middleware(['permission:delete identity']);
        $identity->delete();
    }
}
