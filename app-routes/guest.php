<?php

use Illuminate\Support\Facades\Route;

Route::get('/register', 'RegisterController@showRegistrationForm')->name('show_registration_form');
Route::post('/register', 'RegisterController@register')->name('register');
Route::post('/login', 'LoginController@login')->name('perform_login');
Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::get('/forget_password', 'LoginController@forgetPassword')->name('forget_password');
Route::group(['prefix' => 'delivery_man'], function () {
    Route::get('/confirm/{hash}', 'DeliveryManController@confirm');
    Route::post('/confirm/{hash}/{transaction}', 'DeliveryManController@performConfirm');
    Route::get('/{transaction}/resend_otp', 'DeliveryManController@resendOtp');
});
