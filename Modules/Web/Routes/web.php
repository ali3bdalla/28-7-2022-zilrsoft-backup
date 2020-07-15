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

//

Route::prefix('web-app')->middleware('lang:ar')->name('web.')->group(function() {
    app()->setLocale('ar');
    Route::get('/', 'WebController@index')->name('index');
    Route::prefix('/categories')->name('categories.')->group(function(){
       Route::get('/{category}','CategoryController@show')->name('show');
    });

    Route::prefix('/items')->name('items.')->group(function(){
        Route::get('/{item}','ItemController@show')->name('show');
    });

});
