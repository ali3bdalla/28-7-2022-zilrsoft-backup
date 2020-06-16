<?php
 use Illuminate\Support\Facades\Route;


 Route::resources([
    'sales' => 'SaleController',
    'quotations' => 'QuotationController',
    'purchases' => 'PurchaseController',
    'expenses' => 'ExpenseController'
]);



Route::name('sales.')->prefix('sales')->group(function () {
    Route::get('view/quotations', "SaleController@quotations")->name('quotations');
    Route::get('{sale}/print', "SaleController@print")->name('print');
    Route::get('{beginning}/force_delete', "InventoryController@delete_sale")->name('delete');
    Route::get('{beginning}/return_force_delete', "InventoryController@delete_return_sale")->name('delete_return');
});


Route::name('quotations.')->prefix('quotations')->group(function () {
    Route::get('create/service', "QuotationController@quotations")->name('services');
    Route::get('services_quotations/index', "QuotationController@services_quotations")->name('services_quotations');
});


Route::name('purchases.')->prefix('purchases')->group(function () {
    Route::get('{purchase}/print', "PurchaseController@print")->name('print');
    Route::get('{beginning}/force_delete', "InventoryController@delete_purchase")->name('delete');
    Route::get('{purchase}/clone', "PurchaseController@clone")->name('clone');
    Route::get('{item}/fix_issues', "PurchaseController@fixIssues")->name('fix_issues');
    Route::get('pending/list', "PurchaseController@pending_list")->name('pending');
});


Route::prefix('inventories')->name('inventories.')->group(function () {
    Route::resources([
        'adjust_stock' => 'AdjustStockController',
    ]);
    Route::get('inventory_reconciliation/index', 'AdjustStockController@inventory_reconciliation')->name('inventory_reconciliation');
    Route::get('/', 'InventoryController@index')->name('index');
    Route::prefix('beginning')->name('beginning.')->group(function () {
        Route::get('/', 'InventoryController@beginning_index')->name('index');
        Route::get('/create', 'InventoryController@beginning_create')->name('create');
//				Route::get('{beginning}/edit','InventoryController@beginning_edit')->name('edit');
        Route::post('/store', 'InventoryController@beginning_store')->name('store');
        Route::delete('{beginning}', 'InventoryController@beginning_destroy')->name('destroy');
        Route::get('{beginning}/force_delete', 'InventoryController@beginning_destroy')->name('delete');
        Route::put('{beginning}', 'InventoryController@beginning_return')->name('return');
    });

});
