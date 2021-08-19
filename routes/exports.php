<?php

use Illuminate\Support\Facades\Route;

Route::name('printer')->prefix('printer')->group(function () {
    Route::get('print_receipt/{sale}', 'PrinterController@print_receipt');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::name('printer.')->prefix('printer')->group(function () {
        Route::get('sign_receipt_printer', 'PrinterController@sign_receipt_printer');
        Route::get('printers', 'PrinterController@printers');
        Route::get('print_a4/{invoice}', 'PrinterController@print_a4')->name('a4');
        Route::get('voucher/{voucher}', 'PrinterController@voucher')->name('voucher');
    });
});

Route::get('public-invoice/{invoicePublicIdElementsHash}', 'PrinterController@show_public_invoice')->name('public-invoice.show');
