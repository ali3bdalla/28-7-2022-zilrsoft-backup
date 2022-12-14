<?php

use App\Http\Controllers\Store\API\CartController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => "orders"], function () {
    Route::post('{order}/cancel', 'OrderController@cancelOrder');
    Route::post('{order}/confirm_payment', 'OrderController@confirmPayment');
});

Route::prefix('items')->name('items.')->group(
    function () {
        Route::match(['POST', 'GET'], '/', 'ItemController@index')->name('index');
        Route::match(['POST', 'GET'], '/using_filters', 'ItemController@usingFilters')->name('using_filters');
    }
);
Route::get('shipping_methods', 'ShippingMethodController@index');

Route::prefix('categories')->name('categories.')->group(
    function () {
        Route::match(['POST', 'GET'], '/', 'CategoryController@index')->name('index');
        Route::get('{category}/subcategories', 'CategoryController@subcategories')->name('subcategories');
    }
);

Route::prefix('cart')->name('cart.')->group(
    function () {
        Route::match(['POST', 'GET'], '/get_items_details', 'CartController@getItemDetails')->name('get_items_details');
        Route::post('add_item', [CartController::class, 'addItem']);
        Route::delete('remove_item', [CartController::class, 'removeItem']);
        Route::put('update_quantity', [CartController::class, 'updateQuantity']);
        Route::put('city', [CartController::class, 'updateCity']);
        Route::put('shipping_method', [CartController::class, 'updateShippingMethod']);
        Route::put('shipping_address', [CartController::class, 'updateShippingAddress']);
    }
);
Route::prefix('filters')->name('filters.')->group(
    function () {
        Route::match(['POST', 'GET'], '/', 'FilterController@apiGetFilters')->name('get_filters');
    }
);
Route::middleware('auth:client')->group(
    function () {
        Route::resource('orders', 'OrderController');
    }
);

Route::prefix('{user}')->group(
    function () {
        Route::resource('payment_accounts', 'PaymentAccountController');
    }
);
