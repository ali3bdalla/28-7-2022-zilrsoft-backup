<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category,App\Models\Item;
use Inertia\Inertia;
class CategoryController extends Controller
{
    //

    public function show( Category $category )
    {


        // return $category->children()->pluck('id'); 
        // return $category->getChildrenIncludeMe();
        return Inertia::render('Web/Category/Show',[
            'category' => $category,
            'subcategories' => $category->children()->get(),
            'items' => Item::whereIn('category_id',$category->getChildrenIncludeMe())->with('category')->inRandomOrder()->take(50)->get(),

        ]);
    }
}
