<?php

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/get_test_token',function(){
    return [
        'token' => (Manager::find(1))->createToken(Carbon::now()->toDateTimeString())->plainTextToken
    ];
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/validate',function (Request $request) {
        $request->validate([
            'expo_token' => 'required|string'
        ]);
        Session::put('exp_token',$request->input('exp_token'));
        return [
            "message" => "Ok"
        ]; 
    });

    Route::get('notify',function(){
            
    });
});