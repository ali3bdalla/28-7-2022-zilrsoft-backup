<?php

use Illuminate\Support\Facades\Route;


Route::resource('sales', 'SaleController');
Route::resource('accounts', 'AccountController');
Route::prefix('accounts/{account}')->name('accounts.')->group(function(){
    Route::get('/children', 'AccountController@children')->name('children');
    Route::get('/entities', 'AccountController@entities')->name('entities');

});
Route::resource('items', 'ItemController');
Route::prefix('items/{item}')->name('items.')->group(function(){
    Route::get('/transactions', 'ItemController@transactions')->name('transactions');
});
Route::resource('purchases', 'PurchaseController');
Route::prefix('purchases')->name('purchases.')->group(function(){
    Route::post('/draft', 'PurchaseController@storeDraft')->name('store.draft');
    Route::patch('/{purchase}', 'PurchaseController@storeReturnPurchase')->name('store.return');
});