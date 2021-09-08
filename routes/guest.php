<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'delivery_man'], function () {
    Route::get('/confirm/{hash}', 'DeliveryManController@confirm');
    Route::post('/confirm/{hash}/{transaction}', 'DeliveryManController@performConfirm');
    Route::get('/{transaction}/resend_otp', 'DeliveryManController@resendOtp');
});
Route::name('printer')->prefix('accounting/printer')->group(function () {
    Route::get('print_receipt/{sale}', '\App\Http\Controllers\App\Web\PrinterController@print_receipt');
});
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
