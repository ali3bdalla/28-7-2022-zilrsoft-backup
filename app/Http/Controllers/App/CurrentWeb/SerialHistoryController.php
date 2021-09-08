<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\ItemSerials;
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
            'serial' => 'required|organization_exists:App\Models\ItemSerials,serial'
        ]);

        $serial = ItemSerials::where('serial', $request->serial)->first();
        return view('serials.show', compact('serial'));
    }


}
