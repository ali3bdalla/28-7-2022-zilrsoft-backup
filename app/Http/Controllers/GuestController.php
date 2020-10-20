<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    //

    public function index(Request $request)
    {
        if($request->user())
            return redirect(route('items.index'));

        return redirect(route('login'));
    }
}
