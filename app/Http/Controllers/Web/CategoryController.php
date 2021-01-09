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

        return Inertia::render('Web/Category/Show', [
            'category' => $category,
            'level' => $level,
            'subcategories' => $category->children()->get(),
            'items' => Item::whereIn('category_id', $category->getChildrenIncludeMe())->with('category', 'filters.filter', 'filters.value')->inRandomOrder()->take(50)->get(),

        ]);
    }
}
