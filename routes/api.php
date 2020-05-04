<?php


Route::middleware('guest:api')->group(function () {
    Route::post('/login', "AuthController@login");
    Route::post('/register', "AuthController@register");

});

Route::middleware('auth:api')->group(function () {

});
//Route::get('');


Route::prefix('items')->group(function () {

    Route::get('latest', "itemController@latest");
    Route::get('best', "itemController@best");
    Route::get('offer', "itemController@offer");
    Route::get('trending', "itemController@trending");
    Route::get('top', "itemController@top");
    Route::get('panner', "itemController@panner");

});


Route::prefix('categories')->group(function () {
    Route::get('latest', "CategoryController@latest");
});
