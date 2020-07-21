<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    //

    public function toAppDashboard()
    {
        return redirect(routes('dashboard.login'));
    }

    public function toAppPortal()
    {
        return redirect(route('authentication.login'));
    }
}
