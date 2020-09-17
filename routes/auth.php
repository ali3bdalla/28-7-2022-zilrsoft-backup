<?php
use Illuminate\Support\Facades\Auth;


Route::get('/','GuestController@index')->name('index');
Auth::routes(["verify" => true]);
