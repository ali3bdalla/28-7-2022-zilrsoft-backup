<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'delivery_man'], function () {
    Route::get('/confirm/{hash}', 'DeliveryManController@confirm');
    Route::post('/confirm/{hash}/{transaction}', 'DeliveryManController@performConfirm');
    Route::get('/{transaction}/resend_otp', 'DeliveryManController@resendOtp');
});


Route::get('/login', [\App\Http\Controllers\App\Auth\LoginController::class,'index'])->name('index');
