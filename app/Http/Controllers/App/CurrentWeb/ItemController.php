<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;

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
        $chats = Category::where('parent_id', 0)->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::whereIsVendor(true)->get();
        return view('accounting.items.create', compact('categories', 'isClone', 'vendors'));

    }

    public function edit(Item $item)
    {

        $isClone = 0;
        $chats = Category::where('parent_id', 0)->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::whereIsVendor(true)->get();
        $item['tags'] = $item->tags()->pluck('tag')->toArray();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }


    public function clone(Item $item)
    {
        $isClone = 1;

        $chats = Category::where('parent_id', 0)->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::whereIsVendor(true)->get();
        $item['tags'] = $item->tags()->pluck('tag')->toArray();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }


    public function transactions(Item $item)
    {
        $item->total_stock_amount = $item->total_cost_amount;
        return view('accounting.items.transactions', compact('item'));
    }


    public function serials(Item $item,Request $request)
    {
        $request->validate([
           'status' => 'nullable|in:in_stock,return_sale,return_purchase,sold'
        ]);
        $status = $request->input("status");

        $queryOrder = "CASE WHEN status = 'in_stock' THEN 1 ";
        $queryOrder .= "WHEN status = 'return_sale' THEN 2 ";
        $queryOrder .= "ELSE 3 END";

        $serials = $item->serials()->when($status,function($subQuery) use($status){
            $subQuery->where('status',$status);
        })->orderByRaw($queryOrder)->paginate(20);
        return view('accounting.items.view_serials', compact('item', 'serials','status'));
    }


}
