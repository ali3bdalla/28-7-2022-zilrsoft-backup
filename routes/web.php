<?php


use Illuminate\Support\Facades\Route;
// auth()->loginUsingId(1);
// Route::middleware(['auth'])->get('/',
//     'RedirectController@toAppDashboard');

// Route::middleware('guest')->get('/',
//     'RedirectController@toAppPortal');


Route::resource('sales', 'SaleController');
Route::prefix('dashboard')->name('dashboard.')->group(function()
{
    Route::get('/','HomeController@index')->name('index');
});
