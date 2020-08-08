<?php


use Illuminate\Support\Facades\Route;
Route::middleware(['auth'])->get('/',
    'RedirectController@toAppDashboard');

Route::middleware('guest')->get('/',
    'RedirectController@toAppPortal');
