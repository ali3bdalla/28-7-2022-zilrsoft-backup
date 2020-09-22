<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;

class ItemController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $creators = Manager::all();
        return view('accounting.items.index', compact('categories', 'creators'));
    }


    public function create()
    {
        $isClone = false;
        // $chats = Category::mainOnly()->get();
        // $categories = [];
        // foreach ($chats as $category) {
        //     $category['children'] = Category::getAllParentNestedChildren($category);
        //     $categories[] = $category;
        // }
        $categories = Category::all();
        $vendors = User::where('is_vendor', true)->get();
        return view('accounting.items.create', compact('categories', 'isClone', 'vendors'));

    }

    public function edit(Item $item)
    {

        $isClone = 0;
        $categories = Category::all();
        // $categories = [];
        // foreach ($chats as $category) {
        //     $category['children'] = Category::getAllParentNestedChildren($category);
        //     $categories[] = $category;
        // }
        $vendors = User::where('is_vendor', true)->get();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }


    public function clone(Item $item)
    {
        $isClone = 1;
        $categories = Category::all();

        // $chats = Category::mainOnly()->get();
        // $categories = [];
        // foreach ($chats as $category) {
        //     $category['children'] = Category::getAllParentNestedChildren($category);
        //     $categories[] = $category;
        // }
        $vendors = User::where('is_vendor', true)->get();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }


    public function transactions(Item $item)
    {
        $item->cost = moneyFormatter($item->cost);
        $item->total_stock_amount = moneyFormatter($item->total_cost_amount);
        return view('accounting.items.transactions', compact('item'));
    }


    public function serials(Item $item)
    {
        $serials = $item->serials()->paginate(20);
        return view('accounting.items.view_serials', compact('item', 'serials'));
    }
}
