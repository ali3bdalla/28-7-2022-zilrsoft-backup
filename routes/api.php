<?php

use Illuminate\Support\Facades\Route;


Route::resource('sales', 'SaleController');
Route::prefix('sales')->name('sales.')->group(function () {
    Route::post('/draft', 'SaleController@storeDraft')->name('store.draft');
    Route::patch('/{sale}', 'SaleController@storeReturnSale')->name('store.return');
});
Route::resource('accounts', 'AccountController');
Route::prefix('accounts/{account}')->name('accounts.')->group(function () {
    Route::get('/children', 'AccountController@children')->name('children');
    Route::get('/entities', 'AccountController@entities')->name('entities');

});
Route::resource('entities', 'EntityController');
Route::prefix('entities')->name('entities.')->group(function () {
    Route::get('{account}/transactions', 'EntityController@transactions')->name('transactions');
});
Route::resource('items', 'ItemController');
Route::prefix('items/validations')->name('items.validations.')->group(function () {
    Route::match(['get', 'post'], '/sales_serial', 'ItemController@ValidateSalesSerial')->name('sales_serial');
    Route::match(['get', 'post'], '/return_sales_serial', 'ItemController@ValidateReturnSalesSerial')->name('return_sales_serial');
    Route::match(['get', 'post'], '/return_purchases_serial', 'ItemController@ValidatePurchasesSerial')->name('return_purchases_serial');
    Route::match(['get', 'post'], '/purchases_serial', 'ItemController@ValidatePurchasesSerial')->name('purchases_serial');
});

Route::prefix('items/query')->name('items.query.')->group(function () {
    Route::match(['get', 'post'], '/search', 'ItemController@querySearch')->name('search');
});
Route::prefix('items/{item}')->name('items.')->group(function () {
    Route::get('/transactions', 'ItemController@transactions')->name('transactions');
});
Route::resource('purchases', 'PurchaseController');
Route::prefix('purchases')->name('purchases.')->group(function () {
    Route::post('/draft', 'PurchaseController@storeDraft')->name('store.draft');
    Route::patch('/{purchase}', 'PurchaseController@storeReturnPurchase')->name('store.return');
});


Route::prefix('inventory')->name('inventory.')->group(function (){
    Route::post('/beginning','InventoryController@storeBeginning')->name('beginning.store');
    Route::post('/adjustment','InventoryController@storeAdjustment')->name('adjustment.store');
});



Route::prefix('daily')->name('daily.')->group(function (){
    Route::prefix('reseller')->name('reseller.')->group(function (){
        Route::prefix('closing_accounts')->name('closing_account.')->group(function () {
            Route::post('/','DailyController@storeResellerClosingAccount')->name('index');

        });
    });

});

