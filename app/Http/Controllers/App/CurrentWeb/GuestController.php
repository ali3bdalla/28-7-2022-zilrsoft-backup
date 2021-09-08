<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->user())
            return redirect(route('items.index'));

        return redirect(route('login'));
    }
}
