<?php


//use Illuminate\Support\Facades\DB;
//app()->setLocale('ar');
//DB::unprepared(file_get_contents(base_path('database/database.sql')));
//
//Route::prefix('limit')->middleware('ProtectLimitMiddleware')->group(function () {
//    Route::get('items', 'Limit\ItemController@index');
//    Route::get('items/{item}', 'Limit\ItemController@edit');
//    Route::post('items/{item}', 'Limit\ItemController@update');
//    Route::delete('items/{item}/{attachment}', 'Limit\ItemController@delete_attachment');
//});

//
//Route::namespace('Web')->group(function () {
//    Route::get('/', 'HomeController@home')->name('home');
//    Route::get('/home', 'HomeController@home')->name('home');
//    Route::get('/{item}', 'ItemController@view')->name('view_item');
//});


Route::get('/',function (){
    //route('accounting.login')
    return redirect("/web-app");
});
