<?php

namespace App\Http\Controllers\Store\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function toWeb()
    {
        return redirect(route('web.index', []));
    }

    public function index(): Response
    {
        return Inertia::render(
            'Home/Index',
            [
                'heigest_price' => Item::available()->where('is_published', true)->with('category', 'filters.filter', 'filters.value')->orderBy('online_offer_price', 'desc')->take(15)->get(),
                'offer_item' => Item::where('is_published', true)->inRandomOrder()->hasModelNumber()->first(),
            ]
        );
    }
}
