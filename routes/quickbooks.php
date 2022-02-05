<?php

use App\Http\Controllers\QuickBook\SaleReceiptController;
use App\Http\Livewire\SaleReceiptForm;
use Illuminate\Support\Facades\Route;

//Route::resource("salesreceipts", SaleReceiptController::class);
Route::prefix('sales_receipts')->name('sales_receipts.')->group(
    function () {
        Route::get('/create', SaleReceiptForm::class);
//        Route::get('/create_service', [SaleController::class, 'createServiceDraft'])->name('create.service');
//        Route::get('/{sale}/clone', [SaleController::class, 'clone'])->name('clone');
//        Route::get('/{sale}/to_invoice', [SaleController::class, 'toInvoice'])->name('to_invoice');
    }
);
