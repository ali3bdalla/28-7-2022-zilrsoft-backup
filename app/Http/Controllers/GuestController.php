<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    //

    public function index(Request $request)
    {
        return redirect(route('login'));
    }
}
