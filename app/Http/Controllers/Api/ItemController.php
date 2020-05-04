<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function latest()
    {
        return Item::orderByDesc('id')->paginate(20);
    }
}
