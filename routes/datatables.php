<?php
use Illuminate\Support\Facades\Route;
Route::prefix('datatable')->group(function () {
    Route::get('items', 'ItemController@datatable')->name('items.datatable');
    Route::get('filters', 'FilterController@datatable')->name('filters.datatable');
    Route::get('{filter}/filter_values', 'FilterValuesController@datatable')->name('filter.values.datatable');
    Route::get('identities', 'IdentitiesController@datatable')->name('identities.datatable');
    Route::get('managers', 'ManagerController@datatable')->name('managers.datatable');
    Route::get('branches', 'BranchController@datatable')->name('branches.datatable');
    Route::get('branches/{branch}/departments', 'BranchController@departments_datatable')->name('branches.datatable');
    Route::get('beginning_inventories', 'InventoryController@beginning_datatable')->name('beginning.datatable');
    Route::get('adjust_stock_inventories', 'AdjustStockController@datatable')->name('adjust_stock.datatable');
    Route::get('purchases', 'PurchaseController@datatable')->name('purchases.datatable');
    Route::get('sales', 'SaleController@datatable')->name('sales.datatable');
    Route::get('vouchers', 'VoucherController@datatable')->name('vouchers.datatable');

});
