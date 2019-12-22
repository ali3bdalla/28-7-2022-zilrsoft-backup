<?php

namespace App\Http\Controllers;

use App\ItemSerials;
use Illuminate\Http\Request;

class SerialHistoryController extends Controller
{
    public function index()
    {
        return view('serials.index');
    }


    public function show(Request $request)
    {
        $request->validate([
           'serial'=>'required|exists:item_serials,serial'
        ]);

        $serial = ItemSerials::where('serial',$request->serial)->first();
        return view('serials.show',compact('serial'));
    }


    //
}
