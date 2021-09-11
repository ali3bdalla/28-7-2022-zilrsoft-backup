<?php

use App\Http\Controllers\App\CurrentWeb\AccountController;
use App\Http\Controllers\App\CurrentWeb\DailyController;
use App\Http\Controllers\App\CurrentWeb\DeliveryManController;
use App\Http\Controllers\App\CurrentWeb\EntityController;
use App\Http\Controllers\App\CurrentWeb\FilterController;
use App\Http\Controllers\App\CurrentWeb\FinancialStatementController;
use App\Http\Controllers\App\CurrentWeb\HomeController;
use App\Http\Controllers\App\CurrentWeb\InventoryController;
use App\Http\Controllers\App\CurrentWeb\ItemController;
use App\Http\Controllers\App\CurrentWeb\PurchaseController;
use App\Http\Controllers\App\CurrentWeb\SaleController;
use App\Http\Controllers\App\CurrentWeb\VoucherController;
use App\Http\Controllers\App\Web\FilterController as WebFilterController;
use App\Http\Controllers\App\Web\FilterValuesController as WebFilterValuesController;
use App\Http\Controllers\App\Web\IdentitiesController as WebIdentitiesController;
use App\Http\Controllers\App\Web\InventoryController as WebInventoryController;
use App\Http\Controllers\App\Web\ManagerController as WebManagerController;
use App\Http\Controllers\App\Web\OrderController;
use App\Http\Controllers\App\Web\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard.index');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

Route::resource('sales', SaleController::class);
Route::prefix('sales')->name('sales.')->group(
    function () {
        Route::prefix('drafts')->name('drafts.')->group(
            function () {
                Route::get('/index', [SaleController::class, 'drafts'])->name('index');
                Route::get('/create', [SaleController::class, 'createDraft'])->name('create');
                Route::get('/create_service', [SaleController::class, 'createServiceDraft'])->name('create.service');
                Route::get('/{sale}/clone', [SaleController::class, 'clone'])->name('clone');
                Route::get('/{sale}/to_invoice', [SaleController::class, 'toInvoice'])->name('to_invoice');
            }
        );
    }
);
Route::resource('accounts', AccountController::class);
Route::resource('delivery_men', DeliveryManController::class);
Route::prefix('accounts')->name('accounts.')->group(
    function () {
        Route::prefix('reports')->name('reports.')->group(
            function () {
                Route::get('/index', [AccountController::class, 'reports'])->name('index');
            }
        );
        Route::prefix('{account}')->name('show.')->group(
            function () {
                Route::get('/stock/{item}', [AccountController::class, 'showItem'])->name('item');
                Route::get('/identity/{identity}', [AccountController::class, 'showIdentity'])->name('identity');
            }
        );
    }
);
Route::resource('vouchers', VoucherController::class);
Route::prefix('vouchers/manual')->name('vouchers.')->group(
    function () {
        Route::get('/create-supplier', 'VoucherController@createSupplierVoucher')->name('create.supplier');
    }
);
Route::resource('entities', EntityController::class);
Route::prefix('entities')->name('entities.')->group(
    function () {
        Route::get('user/{account}/{user}', [EntityController::class, 'showUserEntities'])->name('user');
    }
);
Route::prefix('financial_statements')->name('financial_statements.')->group(
    function () {
        Route::get('/', [FinancialStatementController::class, 'index'])->name('index');
        Route::get('trial_balance', [FinancialStatementController::class, 'trailBalance'])->name('trial_balance');
    }
);
Route::resource('items', ItemController::class);
Route::prefix('items/{item}')->name('items.')->group(
    function () {
        Route::get('/transactions', [ItemController::class, 'transactions'])->name('transactions');
        Route::get('/view_serials', [ItemController::class, 'serials'])->name('serials');
        Route::get('/clone', [ItemController::class, 'clone'])->name('clone');
    }
);
Route::resource('purchases', PurchaseController::class);
Route::prefix('purchases')->name('purchases.')->group(
    function () {
        Route::get('view/drafts', [PurchaseController::class, 'drafts'])->name('drafts');
    }
);
Route::resource('entities', EntityController::class);
Route::prefix('inventory')->name('inventory.')->group(
    function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::prefix('adjustments')->name('adjustments.')->group(function () {
            Route::get('/', [InventoryController::class, 'adjustements'])->name('index');
            Route::get('/create', [InventoryController::class, 'createAdjustement'])->name('create');
        });
    }
);
Route::prefix('daily')->name('daily.')->group(
    function () {
        Route::get('/', [DailyController::class, 'begning'])->name('index');
        Route::prefix('reseller')->name('reseller.')->group(
            function () {
                Route::prefix('closing_accounts')->name('closing_accounts.')->group(
                    function () {
                        Route::get('/', [DailyController::class, 'resellerClosingAccountsIndex'])->name('index');
                        Route::get('/create', [DailyController::class, 'createResellerClosingAccount'])->name('create');
                    }
                );
                Route::prefix('accounts_transactions')->name('accounts_transactions.')->group(
                    function () {
                        Route::get('/', [DailyController::class, 'resellerAccountsTransactionsIndex'])->name('index');
                        Route::get('/create', [DailyController::class, 'createResellerAccountTransaction'])->name('create');
                        Route::get('/{transaction}/confirm', [DailyController::class, 'confirmResellerAccountTransaction'])->name('confirm');
                        Route::get('/{transaction}/delete_transaction', [DailyController::class, 'deleteResellerAccountTransaction'])->name('confirm');
                    }
                );
            }
        );
    }
);
Route::prefix('filters')->name('filter')->group(
    function () {
        Route::get('/', [FilterController::class, 'index'])->name('index');
        Route::get('/create', [FilterController::class, 'create'])->name('create');
        Route::get('/{filter}', [FilterController::class, 'show'])->name('show');
        Route::get('/{filter}/edit', [FilterController::class, 'edit'])->name('edit');
    }
);
Route::prefix('/accounting')->name('accounting.')->group(
    function () {
        Route::prefix('/datatable')->group(
            function () {
                Route::get('vouchers', [VoucherController::class, 'datatable'])->name('vouchers.datatable');
                Route::get('filters', [WebFilterController::class, 'datatable'])->name('filters.datatable');
                Route::get('{filter}/filter_values', [WebFilterValuesController::class, 'datatable'])->name('filter.values.datatable');
                Route::get('identities', [WebIdentitiesController::class, 'datatable'])->name('identities.datatable');
                Route::get('managers', [WebManagerController::class, 'datatable'])->name('managers.datatable');
                Route::get('beginning_inventories', [WebInventoryController::class, 'beginning_datatable'])->name('beginning.datatable');
            }
        );
    }
);
Route::middleware('lang:ar')
    ->prefix('accounting')
    ->namespace("\App\Http\Controllers\App\Web")
    ->name('accounting.')
    ->group(function () {
        Route::resources([
            'items' => 'ItemController',
            'kits' => 'KitController',
            'warranty_subscriptions' => 'WarrantySubscriptionsController',
            'categories' => 'CategoryController',
            'filters' => 'FilterController',
            'filter_values' => 'FilterValuesController',
            'managers' => 'ManagerController',
            'identities' => 'IdentitiesController'
        ]);
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


        Route::prefix('categories')->name('categories.')->group(function () {
            Route::post('view/filters', "ProviderController@categories_filters");
            Route::get('{category}/filters', "CategoryController@filters");
            Route::patch('{category}/filters', "CategoryController@update_filters");
            Route::get('{category}/clone', "CategoryController@clone");
        });

        Route::prefix('attachments')->group(function () {
            Route::delete('{attachment}', 'AttachmentController@delete');
        });


        Route::get('roles_permissions', 'ProviderController@roles_permissions');


        Route::prefix('gateways')->name('gateways.')->group(function () {
            Route::get('get_gateways_like_to_manager_name', 'ProviderController@get_gateways_like_to_manager_name')
                ->name('load_manager_gateway');
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


        Route::middleware(['auth', 'verified'])->group(function () {
            Route::name('printer.')->prefix('printer')->group(function () {
                Route::get('sign_receipt_printer', 'PrinterController@sign_receipt_printer');
                Route::get('printers', 'PrinterController@printers');
                Route::get('print_a4/{invoice}', 'PrinterController@print_a4')->name('a4');
                Route::get('voucher/{voucher}', 'PrinterController@voucher')->name('voucher');
            });
        });

        Route::get('public-invoice/{invoicePublicIdElementsHash}', 'PrinterController@show_public_invoice')->name('public-invoice.show');
    });

Route::group(['as' => 'store.', 'prefix' => 'store'], function () {
    Route::prefix('orders')->name('orders.')->group(
        function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('{order}', [OrderController::class, 'show'])->name('show');
            Route::get('{order}/accept-order-as-manager', [OrderController::class, 'acceptOrderAsManager'])->name('accept-order-as-manager');
            Route::get('{order}/view-payment', [OrderController::class, 'viewPayment'])->name('view-payment');
            Route::get('{order}/view-shipping', [OrderController::class, 'viewShipping'])->name('view-shipping');
            Route::get('{order}/activities', [OrderController::class, 'activites'])->name('activites');
            Route::get('{order}/customer-data', [OrderController::class, 'customerData'])->name('customer-data');
        }
    );
    Route::resource('shipping', ShippingController::class);
    Route::group(['as' => 'shipping.', 'prefix' => 'shipping'], function () {
        Route::post('sign-transactions-to-delivery-man', [ShippingController::class, 'signTransactionsToDeliveryMan'])->name('sign-transactions-to-delivery-man');
        Route::post('activate-sign-transactions-to-delivery-man', [ShippingController::class, 'activateSignTransactionsToDeliveryMan'])->name('activate-sign-transactions-to-delivery-man');
        Route::group(['prefix' => '/{shipping}'], function () {
            Route::post('delivery_men', [ShippingController::class, 'storeDeliveryMan'])->name('delivery_men.store');
            Route::get('view-transactions', [ShippingController::class, 'viewTransactions'])->name('view_transactions');
            Route::get('fetch_transactions', [ShippingController::class, 'fetchTransactions'])->name('fetch_transactions');
            Route::get('{transaction}/download', [ShippingController::class, 'downloadTransaction'])->name('download');
            Route::get('create-transaction', [ShippingController::class, 'createTransaction'])->name('create_transaction');
            Route::get('{order}/create-order-transaction', [ShippingController::class, 'createOrderTransaction'])->name('create_order_transaction');
            Route::post('store-transaction', [ShippingController::class, 'storeTransaction'])->name('store_transaction');
            Route::patch('delivery_men/{deliveryMan}', [ShippingController::class, 'updateDeliveryMan'])->name('delivery_men.update');
        });
    });
});




