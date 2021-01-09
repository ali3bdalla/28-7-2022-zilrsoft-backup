<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category, App\Models\Item;
use Inertia\Inertia;

class CategoryController extends Controller
{
    //

    public function show(Category $category)
    {


        $level = 'main';
        if ($category->parent)  $level = 'sub';
        
        $list = [];
        foreach ($category->children()->get() as $key => $child) {
            if(Item::whereIn('category_id', $child->getChildrenIncludeMe())->count())
                $list[] = $child;
        }
        

        return Inertia::render('Web/Category/Show', [
            'category' => $category,
            'level' => $level,
            'subcategories' => $list,
            'items' => Item::whereIn('category_id', $category->getChildrenIncludeMe())->with('category', 'filters.filter', 'filters.value')->inRandomOrder()->take(50)->get(),

        ]);
    }
}
