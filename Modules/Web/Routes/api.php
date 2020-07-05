<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// api/web/items

Route::prefix('/web')->middleware('lang:en')->group(function(){
    Route::post('items',"ItemController@apiGetItems");
    Route::get('filters',"FilterController@apiGetFilters");
    Route::get('subcategories/{category}',"CategoryController@apiGetSubCategories");
});
//Route::middleware('auth:api')->get('/web', function (Request $request) {
//    return $request->user();
//});