<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Inventory\DatatableBeginningRequest;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class InventoryController extends Controller
{

    /**
     * InventoryController constructor.
     */
    public function __construct()
    {
        $this->middleware(['permission:manage inventory']);
    }

    /**
     * @return Factory|View
     */
    public function beginning_index()
    {
        return view('accounting.inventories.beginning.index');
    }

    /**
     * @param DatatableBeginningRequest $request
     *
     * @return mixed
     */
    public function beginning_datatable(DatatableBeginningRequest $request)
    {
        return $request->data();
    }

    /**
     * @return Factory|View
     */
    public function beginning_create()
    {
        $user = User::where([
            ['user_slug', 'beginning-inventory'],
            ['is_system_user', true]
        ])->first();

        $creator = auth()->user()->with('department', 'branch')->first();
        return view('accounting.inventories.beginning.create', compact('user', 'creator'));

    }


}
