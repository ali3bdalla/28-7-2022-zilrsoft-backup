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


Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('load_children/{account}/list', "ChartsController@load_children")->name('load_children');
    Route::get('client/{client}/{account}', "ChartsController@client")->name('client');
    Route::get('vendor/{vendor}/{account}', "ChartsController@vendor")->name('vendor');
    Route::get('item/{item}/{account}', "ChartsController@item")->name('item');
    Route::get('{account}/delete', "ChartsController@delete");
    Route::get('{account}/transactions_datatable', "ChartsController@transactions_datatable")->name('transactions_datatable');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('index', "ChartsController@reports")->name('index');
        Route::get('{account}/result', "ChartsController@reports_result")->name('result');
    });
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

