<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'delivery_man'], function () {
    Route::get('/confirm/{hash}', 'DeliveryManController@confirm');
    Route::post('/confirm/{hash}/{transaction}', 'DeliveryManController@performConfirm');
    Route::get('/{transaction}/resend_otp', 'DeliveryManController@resendOtp');
});

Auth::routes();
