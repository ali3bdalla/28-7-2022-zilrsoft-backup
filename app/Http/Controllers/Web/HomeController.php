<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Item;

class HomeController extends Controller
{
    public function home()
    {


//,
        return view('web.home.index', [
            'active_items' => Item::higherSalesItemsQuery(),
            'banner_item' => Item::bannerItemQuery(),
            'new_items' => Item::latestItemsQuery(),
            'best_items' => Item::formattedCollectionQuery('price', 'desc'),
            'package_items' => Item::formattedPackageCollectionQuery('price', 'desc'),

        ]);
    }
    //
}
