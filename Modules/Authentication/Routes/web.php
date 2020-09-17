<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//dd(auth()->user());
Route::prefix('authentication')->name('authentication.')->group(function() {
    Route::middleware(['guest'])->group(function(){
        Route::get('/register', 'RegisterController@showRegistrationForm')->name('show_registration_form');
        Route::post('/register', 'RegisterController@register')->name('register');
        Route::post('/login', 'LoginController@login')->name('perform_login');
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
    });
    Route::middleware('auth')->post('/logout', 'AuthenticationController@logout')->name('logout');
});
