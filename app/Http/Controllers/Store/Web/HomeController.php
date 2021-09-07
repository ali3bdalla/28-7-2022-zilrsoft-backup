<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function toWeb()
    {
        return redirect(route('web.index', []));
    }

    public function index()
    {
        return Inertia::render(
            'Home/Index',
            [
                'heigest_price' => Item::available()->with('category', 'filters.filter', 'filters.value')->orderBy('online_offer_price', 'desc')->take(15)->get(),
                'offer_item' => Item::inRandomOrder()->hasModelNumber()->first(),
            ]
        );
    }
}
