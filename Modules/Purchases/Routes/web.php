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

Route::prefix('purchases')->name('purchases.')->group(function() {
    Route::get('/', 'PurchaseController@index')->name('index');
    Route::get('/create', 'PurchaseController@create')->name('create');
    Route::get('/pending', 'PurchaseController@pending')->name('pending');

    Route::get('statistics/get_pending_counts', 'StatisticsController@getPendingCounts')->name('get_pending_counts');
});
