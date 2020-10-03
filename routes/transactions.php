<?php
 use Illuminate\Support\Facades\Route;

 Route::resources([
    'reports' => 'ReportController',
    'accounts' => 'ChartsController',
    'transactions' => 'TransactionsController'
]);


Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('add/create', "TransactionsController@create")->name('add.create');

});
Route::prefix('reseller_daily')->name('reseller_daily.')->group(function () {
    Route::get('account_close', "ResellerDailyTransactions@dailyShutdownShowForm")->name('account_close');
    Route::post('account_close', "ResellerDailyTransactions@dailyShutdownSubmitForm")->name('account_close_store');
    Route::get('account_close_list', "ResellerDailyTransactions@dailyShutdownList")->name('account_close_list');
    Route::get('transfer_list', "ResellerDailyTransactions@transferList")->name('transfer_list');
    Route::get('transfer_amounts', "ResellerDailyTransactions@transferShowForm")->name('transfer_amounts');
    Route::post('transfer_amounts', "ResellerDailyTransactions@transferSubmitForm")->name('transfer_amounts_store');
    Route::get('{transaction}/confirm_transaction', "ResellerDailyTransactions@confirmTransferTransactions")->name
    ('confirm_transaction');
    Route::get('{transaction}/delete_transaction', "ResellerDailyTransactions@deleteTransferTransactions")->name
    ('delete_transaction');
});

