<?php

use Illuminate\Support\Facades\Route;


// Route::resource('orders', 'OrderController');
Route::prefix('orders')->name('orders.')->group(
    function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::get('{order}', 'OrderController@show')->name('show');
        Route::get('{order}/accept-order-as-manager', 'OrderController@acceptOrderAsManager')->name('accept-order-as-manager');
        Route::get('{order}/view-payment', 'OrderController@viewPayment')->name('view-payment');
        Route::get('{order}/view-shipping', 'OrderController@viewShipping')->name('view-shipping');
        Route::get('{order}/activities', 'OrderController@activites')->name('activites');
        Route::get('{order}/customer-data', 'OrderController@customerData')->name('customer-data');
    }
);


Route::resource('shipping', 'ShippingController');
Route::prefix('/shipping/{shipping}')->name('shipping.')->group(function () {
    Route::post('delivery_men', 'ShippingController@storeDeliveryMan')->name('delivery_men.store');
    Route::get('view-transactions', 'ShippingController@viewTransactions')->name('view_transactions');
    Route::get('create-transaction', 'ShippingController@createTransaction')->name('create_transaction');
    Route::patch('delivery_men/{deliveryMan}', 'ShippingController@updateDeliveryMan')->name('delivery_men.update');
});