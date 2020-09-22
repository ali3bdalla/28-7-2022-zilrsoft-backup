<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\FetchItemsRequest;
use App\Http\Requests\Items\QueryItemsRequest;
use App\Http\Requests\Items\ValidateSerialRequest;
use App\Http\Resources\InvoiceItem\InvoiceItemCollection;
use App\Models\Item;

class ItemController extends Controller
{
    //

    public function index(FetchItemsRequest $request)
    {
        return $request->getData();
    }

    public function transactions(Item $item)
    {
        $items = $item->pipline()->with('user', 'creator')->paginate(50);
        return new InvoiceItemCollection($items);
    }


    public function ValidateSalesSerial(ValidateSerialRequest $request)
    {
        return $request->sale();
    }

    public function ValidateReturnSalesSerial(ValidateSerialRequest $request)
    {
        return $request->returnSale();
    }


    public function ValidatePurchasesSerial(ValidateSerialRequest $request)
    {
        return $request->purchase();
    }


    public function ValidateReturnPurchasesSerial(ValidateSerialRequest $request)
    {
        return $request->returnPurchase();
    }

    public function querySearch(QueryItemsRequest $request)
    {
        return $request->results();
    }

}
