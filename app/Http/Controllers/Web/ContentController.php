<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    //

    public function contact()
    {

        return Inertia::render('Web/Content/Contact');
    }
    public function about()
    {

        return Inertia::render('Web/Content/AboutUs');
    }
}
