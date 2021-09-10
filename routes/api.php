<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2', 'as' => 'v2.'], function () {
    Route::get('items/search', 'ItemController@search');
});

Route::middleware('auth')->group(
    function () {
        Route::resource('orders', 'OrderController');
        Route::prefix('orders/{order}')->name('orders.')->group(
            function () {
                Route::post('sign-to-delivery-man', 'OrderController@signToDeliveryMan');
                Route::post('activate-sign-to-delivery-man', 'OrderController@activateSignToDeliveryMan');
            }
        );

        Route::prefix('notifications')->namespace('Notifications')->name('notifications.')->group(
            function () {
                Route::prefix('orders')->name('orders.')->group(
                    function () {
                        Route::get('/orders/', 'OrderController@notificationList')->name('order.list');
                    }
                );
                Route::prefix('transactions')->name('transactions.')->group(
                    function () {
                        Route::get('/issued', 'TransactionNotificationController@issued')->name('issued');
                    }
                );
                Route::prefix('orders')->name('orders.')->group(
                    function () {
                        Route::get('pending', 'OrderNotificationController@pending')->name('pending');
                        Route::get('paid', 'OrderNotificationController@paid')->name('paid');
                    }
                );
            }
        );

        Route::resource('vouchers', 'VoucherController');
        Route::resource('sales', 'SaleController');
        Route::prefix('sales')->name('sales.')->group(
            function () {
                Route::post('/draft', 'SaleController@storeDraft')->name('store.draft');
                Route::patch('/{sale}', 'SaleController@storeReturnSale')->name('store.return');
            }
        );
        Route::apiResource('accounts', 'AccountController');
        Route::group(['prefix' => 'accounts/{account}', 'as' => 'accounts.'], function () {
            Route::get('reports', 'AccountController@report')->name('report');
            Route::get('transactions', 'AccountController@transactions')->name('transactions');
        });
        Route::prefix('financial_statements')->name('financial_statements.')->group(
            function () {
                Route::get('trial_balance', 'FinancialStatementController@trailBalance')->name('trial_balance');
            }
        );

        Route::resource('entities', 'EntityController');
        Route::prefix('entities')->name('entities.')->group(
            function () {
                Route::get('{account}/transactions', 'EntityController@transactions')->name('transactions');
            }
        );
        Route::resource('items', 'ItemController');
        Route::prefix('items')->name('items.')->group(
            function () {
                Route::post('/add_images', 'ItemController@addImage')->name('add_images');
                Route::prefix('validations')->name('validations.')->group(function () {
                    Route::match(['get', 'post'], '/sales_serial', 'ItemController@ValidateSalesSerial')->name('sales_serial');
                    Route::match(['get', 'post'], '/return_sales_serial', 'ItemController@ValidateReturnSalesSerial')->name('return_sales_serial');
                    Route::match(['get', 'post'], '/return_purchases_serial', 'ItemController@ValidatePurchasesSerial')->name('return_purchases_serial');
                    Route::match(['get', 'post'], '/purchases_serial', 'ItemController@ValidatePurchasesSerial')->name('purchases_serial');
                    Route::match(['get', 'post'], '/unique_barcode', 'ItemController@validateUniqueBarcode')->name('unique_barcode');
                });
            }
        );
        Route::prefix('items/query')->name('items.query.')->group(
            function () {
                Route::match(['get', 'post'], '/search', 'ItemController@querySearch')->name('search');
            }
        );
        Route::prefix('items/{item}')->name('items.')->group(
            function () {
                Route::get('/transactions', 'ItemController@transactions')->name('transactions');
            }
        );
        Route::resource('purchases', 'PurchaseController');
        Route::prefix('purchases')->name('purchases.')->group(
            function () {
                Route::get('/fetch/pending_dropbox_purchases', 'PurchaseController@pendingDropBoxPurchases')->name('pending_dropbox_purchases');
                Route::post('/draft', 'PurchaseController@storeDraft')->name('store.draft');
                Route::patch('/{purchase}', 'PurchaseController@storeReturnPurchase')->name('store.return');
            }
        );

        Route::prefix('inventory')->name('inventory.')->group(
            function () {
                Route::post('beginning', 'InventoryController@storeBeginning')->name('beginning.store');
                Route::post('adjustments', 'InventoryController@storeAdjustment')->name('adjustments.store');
                Route::get('adjustments', 'InventoryController@adjustments')->name('adjustments.index');
            }
        );

        Route::prefix('daily')->name('daily.')->group(
            function () {
                Route::prefix('reseller')->name('reseller.')->group(
                    function () {
                        Route::prefix('closing_accounts')->name('closing_account.')->group(
                            function () {
                                Route::post('/', 'DailyController@storeResellerClosingAccount')->name('store');
                            }
                        );
                        Route::prefix('/accounts_transactions')->name('accounts_transactions.')->group(
                            function () {
                                Route::post('/', 'DailyController@storeResellerAccountTransaction')->name('store');
                            }
                        );
                    }
                );
            }
        );

        Route::prefix('items/{item}')->name('items.')->group(
            function () {
                Route::get('/view_serials', 'ItemController@serials')->name('serials');
                Route::get('/clone', 'ItemController@clone')->name('clone');
            }
        );

        Route::prefix('filters')->name('filters.')->group(
            function () {
                Route::post('/', 'FilterController@store')->name('store');
                Route::match(['PUT', 'PATCH', 'put', 'patch', 'post'], '/{filter}/update', 'FilterController@update')->name('update');
            }
        );
    }
);

