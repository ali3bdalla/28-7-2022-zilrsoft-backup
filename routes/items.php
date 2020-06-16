<?php
    use Illuminate\Support\Facades\Route;

    Route::resources([ 'items' => 'ItemController','kits' => 'KitController','warranty_subscriptions' => 'WarrantySubscriptionsController']);
    Route::name('items.')->prefix('items')->group(function () {
        Route::get('view/barcode', 'ItemController@barcode')->name('barcode');
        Route::post('{item}/attachments', 'ItemController@upload_attachments')->name('upload_attachments');
        Route::get('view/show_item_barcode', 'ItemController@show_item_barcode')->name('show_item_barcode');
        Route::get('view/serial_activities', 'ItemController@serial_activities')->name('serial_activities');
        Route::get('view/show_serial_activities', 'ItemController@show_serial_activities')->name('show_serial_activities');
        Route::get('{item}/clone', 'ItemController@clone')->name('clone');
        Route::get('{item}/transactions', 'ItemController@transactions')->name('transactions');
        Route::get('{item}/transactions_datatable', 'ItemController@transactions_datatable')->name('transactions_datatable');
        Route::get('{item}/view_serials', 'ItemController@view_serials')->name('view_serials');
        Route::name('helper.')->name('helper.')->prefix('helper')->group(function () {
            Route::match(['get', 'post'], 'validate_barcode', 'ItemController@validate_barcode')->name
            ('validate_barcode');
            Route::post('activate_items', 'ItemController@activate_items')->name('activate_items');
            Route::match(['get', 'post'], 'query_find_items', 'ProviderController@query_find_items')->name
            ('query_find_items');
            Route::match(['get', 'post'], 'query_validate_purchase_serial', 'ProviderController@query_validate_purchase_serial')->name
            ('query_validate_purchase_serial');
            Route::match(['get', 'post'], 'query_validate_sale_serial', 'ProviderController@query_validate_sale_serial')->name
            ('query_validate_purchase_serial');
            Route::match(['get', 'post'], 'query_validate_return_sale_serial', 'ProviderController@query_validate_return_sale_serial')->name
            ('query_validate_purchase_serial');
            Route::match(['get', 'post'], 'query_validate_return_purchase_serial', 'ProviderController@query_validate_return_purchase_serial')->name
            ('query_validate_purchase_serial');

        });
    });



    Route::name('kits.')->prefix('kits')->group(function () {
        Route::name('helper.')->name('helper.')->prefix('helper')->group(function () {
            Route::get('get_kit_amounts/{kit}', 'ProviderController@get_kit_amounts')->name('get_kit_amounts');

        });
    });

