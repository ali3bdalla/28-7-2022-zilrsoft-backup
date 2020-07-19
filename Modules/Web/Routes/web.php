<?php

Route::prefix('web-app')->middleware('lang:en')->name('web.')->group(function() {
    Route::get('/', 'WebController@index')->name('index');
    Route::prefix('/categories')->name('categories.')->group(function(){
       Route::get('/{category}','CategoryController@show')->name('show');
    });

    Route::prefix('/items')->name('items.')->group(function(){
        Route::get('/{item}','ItemController@show')->name('show');
    });

});
