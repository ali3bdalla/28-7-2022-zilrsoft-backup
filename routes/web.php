<?php


use Illuminate\Support\Facades\Route;
 auth()->loginUsingId(1);
Route::middleware(['auth'])->get('/',
    'RedirectController@toAppDashboard');

Route::middleware('guest')->get('/',
    'RedirectController@toAppPortal');
