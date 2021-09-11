<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entities\StoreEntityRequest;

class EntityController extends Controller
{

    public function store(StoreEntityRequest $request)
    {
        return $request->store();
    }

}
