<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    //

    public function contact()
    {

        return Inertia::render('Content/Contact');
    }
    public function about()
    {

        return Inertia::render('Content/Content',[
            'content' => __('store.content.about_us_content')
        ]);
    }

    public function terms()
    {

        return Inertia::render('Content/Content',[
            'content' => __('store.content.terms_and_conditions_content')
        ]);
    }

    public function privacy()
    {

        return Inertia::render('Content/Content',[
            'content' => __('store.content.privacy_content')
        ]);
    }
}
