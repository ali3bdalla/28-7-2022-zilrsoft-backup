<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKitRequest;
use App\Item;
use App\User;
use Illuminate\Http\Request;

class KitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items =  Item::kits()->orderBy('id','desc')->paginate(10);
        return view('kits.index',compact('items'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::where('is_client',true)->get()->toArray();
        return view('kits.create',compact('clients'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateKitRequest $request)
    {
        //

        return $request->save();
    }


    /**
     * Show the form for editing the specified resource. and edit
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $kit)
    {

        $items = $kit->items()->with('item')->get();
        $creator = $kit->creator;
        $data = $kit->data;
        


        $clients = User::where('is_client',true)->get()->toArray();

        $kit['items'] = $items;
        $kit['creator'] = $creator;
        $kit['data'] = $data;

        
//        return  $kit;
        
        
        return view('kits.edit',compact('clients','kit'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $kit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $kit)
    {
        $kit->items()->delete();
        $kit->data()->delete();
        $kit->delete();
        return  back();
        //
    }
}
