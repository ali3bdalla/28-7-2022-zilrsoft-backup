<?php

use App\Http\Controllers\BackEnd\Store\OrderController;
use App\Http\Controllers\BackEnd\Store\ShippingController;
use Illuminate\Support\Facades\Route;


Route::prefix('orders')->name('orders.')->group(
    function () {
        Route::get('/',[OrderController::class,'index'])->name('index');
        Route::get('{order}', [OrderController::class,'show'])->name('show');
        Route::get('{order}/accept-order-as-manager', [OrderController::class,'acceptOrderAsManager'])->name('accept-order-as-manager');
        Route::get('{order}/view-payment', [OrderController::class,'viewPayment'])->name('view-payment');
        Route::get('{order}/view-shipping', [OrderController::class,'viewShipping'])->name('view-shipping');
        Route::get('{order}/activities',[OrderController::class,'activites'])->name('activites');
        Route::get('{order}/customer-data', [OrderController::class,'customerData'])->name('customer-data');
    }
);


Route::resource('shipping', ShippingController::class);
Route::prefix('/shipping')->name('shipping.')->group(function () {
    Route::post('sign-transactions-to-delivery-man',[ShippingController::class,'signTransactionsToDeliveryMan'])->name('sign-transactions-to-delivery-man');
    Route::post('activate-sign-transactions-to-delivery-man', [ShippingController::class,'activateSignTransactionsToDeliveryMan'])->name('activate-sign-transactions-to-delivery-man');
    Route::prefix('/{shipping}')->group(function () {
        Route::post('delivery_men', [ShippingController::class,'storeDeliveryMan'])->name('delivery_men.store');
        Route::get('view-transactions', [ShippingController::class,'viewTransactions'])->name('view_transactions');
        Route::get('fetch_transactions', [ShippingController::class,'fetchTransactions'])->name('fetch_transactions');
        Route::get('{transaction}/download', [ShippingController::class,'downloadTransaction'])->name('download');
        Route::get('create-transaction', [ShippingController::class,'createTransaction'])->name('create_transaction');
        Route::get('{order}/create-order-transaction', [ShippingController::class,'createOrderTransaction'])->name('create_order_transaction');
        Route::post('store-transaction', [ShippingController::class,'storeTransaction'])->name('store_transaction');
        Route::patch('delivery_men/{deliveryMan}', [ShippingController::class,'updateDeliveryMan'])->name('delivery_men.update');
    });
});
