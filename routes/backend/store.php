<?php

use Illuminate\Support\Facades\Route;


Route::resource('orders', 'OrderController');
Route::prefix('orders')->name('orders.')->group(
    function () {
        Route::get('{order}/confirm', 'OrderController@confirm');
    }
);


Route::resource('shipping', 'ShippingController');
Route::prefix('/shipping/{shipping}')->name('shipping.')->group(function () {
    Route::post('delivery_men', 'ShippingController@storeDeliveryMan')->name('delivery_men.store');
    Route::patch('delivery_men/{deliveryMan}', 'ShippingController@updateDeliveryMan')->name('delivery_men.update');
});