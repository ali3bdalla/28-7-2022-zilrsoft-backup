<?php

namespace Modules\Web\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Web\Http\Requests\Item\ApiGetItemsRequest;

class ItemController extends Controller
{

    public function show($id)
    {
        return view('web::show');
    }


    public function apiGetItems(ApiGetItemsRequest $request)
    {
       return $request->getData();
    }
}
