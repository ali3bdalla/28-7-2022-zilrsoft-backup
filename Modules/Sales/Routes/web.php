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
//auth()->loginUsingId(1);


use Illuminate\Support\Facades\Route;


//auth()->loginUsingId(1);
Route::prefix('sales')->name('sales.')->middleware('auth')->group(function() {
    // Route::get('/', 'SaleController@index')->name('index');
    // Route::get('/create', 'CreateController@showCreateForm')->name('create');
    // Route::get('/create', 'CreateController@showCreateForm')->name('create');
    Route::post('/', 'CreateController@store')->name('store');
    Route::match(['PATCH','PUT'],'/{sale}', 'RetrunController@return')->name('return');
});
