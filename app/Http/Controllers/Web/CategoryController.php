<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category, App\Models\Item;
use Inertia\Inertia;

class CategoryController extends Controller
{
    //

    public $breadcrumb;

    
  

    public function show(Category $category)
    {

        $this->breadcrumb [] = [
            'title' => trans('store.header.home')  . ' ',
            "url" => '/web'
        ];
         $level = 'sub';

        $list = [];
        foreach ($category->children()->get() as $key => $child) {
            if (Item::whereIn('category_id', $child->getChildrenIncludeMe())->count())
                $list[] = $child;
        }

        $this->fillBreadcrumb($category,$category->id);


        return Inertia::render('Web/Category/Show', [
            'category' => $category,
            'breadcrumb' => $this->breadcrumb,
            'level' => $level,
            'subcategories' => $list,
            // 'items' => Item::whereIn('category_id', $category->getChildrenIncludeMe())->with('category', 'filters.filter', 'filters.value')->inRandomOrder()->take(50)->get(),

        ]);
    }


    private function fillBreadcrumb($category,$inActiveId)
    {
        if ($category->parent)
            $this->fillBreadcrumb($category->parent,$inActiveId);


        // $this->breadcrumb[] = [
        //     'title' => "/",

        // ];
        $this->breadcrumb[] = [
            'title' => $category->locale_name,
            'url' =>  '/web/categories/' . $category->id

        ];
    }
}
