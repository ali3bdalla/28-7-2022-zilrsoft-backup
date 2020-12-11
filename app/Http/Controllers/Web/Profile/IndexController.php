<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    //

    public function index(Request  $request)
    {
        return Inertia::render('Web/Profile/Index',[
            'user' => $request->user()
        ]);
    }
}
