<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entities\FetchEntitiesRequest;
use App\Http\Requests\Entities\StoreEntityRequest;
use App\Models\Account;

class EntityController extends Controller
{

    public function store(StoreEntityRequest $request)
    {
        return $request->store();
    }


    public function transactions(Account $account, FetchEntitiesRequest $request)
    {
        return $request->getData($account);

    }

}
