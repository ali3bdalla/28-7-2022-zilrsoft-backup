<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\FetchAccountsRequest;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;

class AccountController extends Controller
{
    //

    public function index(FetchAccountsRequest $request)
    {
        return $request->getData();
    }

    public function children(Account $account)
    {
        $children = $account->children()->withCount('children')->orderBy('sorting_number', 'desc')->orderBy('id', 'ASC')->get();
        return $children;
    }

    public function store(StoreAccountRequest $request)
    {
        return $request->store();
    }

    public function show(Account $account)
    {

    }

    public function update(UpdateAccountRequest $request, Account $account)
    {

    }
}
