<?php

namespace Modules\Web\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Web\Http\Requests\Item\ApiGetItemsRequest;

class ItemController extends Controller
{

    public function show(Item $item)
    {
        $relatedItems = $item->category->items()->inRandomOrder()->take(20)->get();
        return view('web::items.show',compact('item','relatedItems'));
    }


    public function apiGetItems(ApiGetItemsRequest $request)
    {
       return $request->getData();
    }
}
