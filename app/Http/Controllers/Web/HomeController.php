<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function toWeb()
    {
        return redirect(route('web.index', []));
    }
    public function index()
    {
        $offerItem = Item::inRandomOrder()->hasModelNumber()->first();
        // $modelFilter = $offerItem->filters()->where('filter_id', 38)->first();
        // if ($modelFilter) {
        //     if ($modelFilter->value) {
        //         $offerItem->name = str_replace($modelFilter->value->name, "", $offerItem->name);
        //         $offerItem->ar_name = str_replace($modelFilter->value->ar_name, "", $offerItem->ar_name);
        //     }
        // }

        // return $offerItem->filters()->where('filter_id',38)->first()->value;
        return Inertia::render(
            'Web/Home/Index',
            [
            // 'products_count' => Item::count(),
            // 'params' => [],
            // 'latest' => Item::available()->with('category', 'filters.filter', 'filters.value')->orderBy('created_at', 'desc')->take(15)->get(),
            'heigest_price' => Item::available()->with('category', 'filters.filter', 'filters.value')->orderBy('online_offer_price', 'desc')->take(15)->get(),
            'offer_item' => $offerItem
            // 'items' => Item::with('category', 'filters.filter', 'filters.value')->inRandomOrder()->take(20)->get(),
            ]
        );
    }
}
