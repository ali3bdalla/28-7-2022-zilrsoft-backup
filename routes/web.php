<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/','GuestController@index')->name('index');
Route::middleware('guest')->group(function(){
    Auth::routes(["verify" => true]);
});
Route::resource('sales', 'SaleController');
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', 'HomeController@index')->name('index');
});
Route::resource('accounts', 'AccountController');
Route::prefix('accounts/{account}/view')->name('accounts.show.')->group(function(){
    Route::get('/stock', 'AccountController@showStock')->name('stock');
    Route::get('/{item}/item', 'AccountController@showItem')->name('item');

});


Route::prefix('entities')->name('entities.')->group(function () {
    Route::get('user/{account}/{user}', "EntityController@showUserEntities")->name('user');
});



Route::resource('entities', 'EntityController');
Route::prefix('financial_statements')->name('financial_statements.')->group(function(){
    Route::get('/', 'FinancialStatementController@index')->name('index');
    Route::get('trial_balance', 'FinancialStatementController@trailBalance')->name('trial_balance');
});
Route::resource('items', 'ItemController');
Route::prefix('items/{item}')->name('items.')->group(function(){
    Route::get('/transactions', 'ItemController@transactions')->name('transactions');
    Route::get('/view_serials', 'ItemController@serials')->name('serials');
});
Route::resource('purchases', 'PurchaseController');
Route::prefix('purchases')->name('purchases.')->group(function(){
    Route::get('view/drafts', 'PurchaseController@drafts')->name('drafts');
});

Route::resource('entities', 'EntityController');



Route::prefix('inventory')->name('inventory.')->group(function (){
    Route::get('/','InventoryController@begning')->name('index');
});


Route::prefix('daily')->name('daily.')->group(function (){
    Route::get('/','DailyController@begning')->name('index');
    Route::prefix('reseller')->name('reseller.')->group(function (){
        Route::prefix('closing_accounts')->name('closing_accounts.')->group(function () {
            Route::get('/','DailyController@resellerClosingAccountsIndex')->name('index');
            Route::get('/create','DailyController@createResellerClosingAccount')->name('create');

        });
    });

});

