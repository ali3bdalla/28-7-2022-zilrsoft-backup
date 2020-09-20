<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\FetchItemsRequest;
use App\Models\Item;

class ItemController extends Controller
{
    //

     /**
     * @param DatatableRequest $request
     *
     * @return LengthAwarePaginator
     */
    public function index(FetchItemsRequest $request)
    {
        return $request->getData();
    }

    public function transactions(Item $item)
    {
        return $item->pipline()->with('user','creator')->paginate(50);
    }

}
