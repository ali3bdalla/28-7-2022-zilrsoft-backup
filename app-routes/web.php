<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', 'HomeController@index')->name('dashboard.index');
Route::post('/logout', 'HomeController@logout')->name('logout');
Route::resource('sales', 'SaleController');
Route::prefix('sales')->name('sales.')->group(
    function () {
        Route::prefix('drafts')->name('drafts.')->group(
            function () {
                Route::get('/index', 'SaleController@drafts')->name('index');
                Route::get('/create', 'SaleController@createDraft')->name('create');
                Route::get('/create_service', 'SaleController@createServiceDraft')->name('create.service');
                Route::get('/{sale}/clone', 'SaleController@clone')->name('clone');
                Route::get('/{sale}/to_invoice', 'SaleController@toInvoice')->name('to_invoice');
            }
        );
    }
);
Route::resource('accounts', 'AccountController');
Route::resource('delivery_men', 'DeliveryManController');
Route::prefix('accounts')->name('accounts.')->group(
    function () {
        Route::prefix('reports')->name('reports.')->group(
            function () {
                Route::get('/index', 'AccountController@reports')->name('index');
            }
        );
        Route::prefix('{account}')->name('show.')->group(
            function () {
                Route::get('/stock/{item}', 'AccountController@showItem')->name('item');
                Route::get('/identity/{identity}', 'AccountController@showIdentity')->name('identity');
            }
        );
    }
);
Route::resource('vouchers', 'VoucherController');
Route::prefix('vouchers/manual')->name('vouchers.')->group(
    function () {
        Route::get('/create-supplier', 'VoucherController@createSupplierVoucher')->name('create.supplier');
    }
);
Route::resource('entities', 'EntityController');
Route::prefix('entities')->name('entities.')->group(
    function () {
        Route::get('user/{account}/{user}', 'EntityController@showUserEntities')->name('user');
    }
);
Route::prefix('financial_statements')->name('financial_statements.')->group(
    function () {
        Route::get('/', 'FinancialStatementController@index')->name('index');
        Route::get('trial_balance', 'FinancialStatementController@trailBalance')->name('trial_balance');
    }
);
Route::resource('items', 'ItemController');
Route::prefix('items/{item}')->name('items.')->group(
    function () {
        Route::get('/transactions', 'ItemController@transactions')->name('transactions');
        Route::get('/view_serials', 'ItemController@serials')->name('serials');
        Route::get('/clone', 'ItemController@clone')->name('clone');
    }
);
Route::resource('purchases', 'PurchaseController');
Route::prefix('purchases')->name('purchases.')->group(
    function () {
        Route::get('view/drafts', 'PurchaseController@drafts')->name('drafts');
    }
);

Route::resource('entities', 'EntityController');
Route::prefix('inventory')->name('inventory.')->group(
    function () {
        Route::get('/', 'InventoryController@index')->name('index');
        Route::get('/create', 'InventoryController@create')->name('create');
        Route::prefix('adjustments')->name('adjustments.')->group(function () {
            Route::get('/', 'InventoryController@adjustements')->name('index');
            Route::get('/create', 'InventoryController@createAdjustement')->name('create');
        });
    }
);
Route::prefix('daily')->name('daily.')->group(
    function () {
        Route::get('/', 'DailyController@begning')->name('index');
        Route::prefix('reseller')->name('reseller.')->group(
            function () {
                Route::prefix('closing_accounts')->name('closing_accounts.')->group(
                    function () {
                        Route::get('/', 'DailyController@resellerClosingAccountsIndex')->name('index');
                        Route::get('/create', 'DailyController@createResellerClosingAccount')->name('create');
                    }
                );
                Route::prefix('accounts_transactions')->name('accounts_transactions.')->group(
                    function () {
                        Route::get('/', 'DailyController@resellerAccountsTransactionsIndex')->name('index');
                        Route::get('/create', 'DailyController@createResellerAccountTransaction')->name('create');
                        Route::get('/{transaction}/confirm', 'DailyController@confirmResellerAccountTransaction')->name('confirm');
                        Route::get('/{transaction}/delete_transaction', 'DailyController@deleteResellerAccountTransaction')->name('confirm');
                    }
                );
            }
        );
    }
);
Route::prefix('filters')->name('filter')->group(
    function () {
        Route::get('/', 'FilterController@index')->name('index');
        Route::get('/create', 'FilterController@create')->name('create');
        Route::get('/{filter}', 'FilterController@show')->name('show');
        Route::get('/{filter}/edit', 'FilterController@edit')->name('edit');
    }
);
Route::prefix('/accounting')->name('accounting.')->namespace('Accounting')->group(
    function () {
        Route::resources(
            [
                'expenses' => 'ExpenseController',
            ]
        );
        Route::prefix('/datatable')->group(
            function () {
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
            }
        );
    }
);

Route::middleware('lang:ar')
    ->prefix('accounting')
    ->namespace("\App\Http\Controllers\App\Web")
    ->name('accounting.')
    ->group(function () {
        Route::resources(['items' => 'ItemController', 'kits' => 'KitController', 'warranty_subscriptions' => 'WarrantySubscriptionsController']);
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


        Route::resources([
            'categories' => 'CategoryController',
            'filters' => 'FilterController',
            'filter_values' => 'FilterValuesController'
        ]);
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::post('view/filters', "ProviderController@categories_filters");
            Route::get('{category}/filters', "CategoryController@filters");
            Route::patch('{category}/filters', "CategoryController@update_filters");
            Route::get('{category}/clone', "CategoryController@clone");
        });


        Route::resources(['settings' => 'SettingController', 'branches' => 'BranchController']);

        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('{branch}/departments', "BranchController@departments")->name('departments.index');
            Route::get('{branch}/departments/create', "BranchController@create_department")->name('departments.create');
            Route::post('{branch}/departments', "BranchController@store_department")->name('departments.store');
            Route::delete('{branch}/departments/{department}', "BranchController@destroy_department")->name('departments.delete');
            Route::get('{branch}/departments/{department}/edit', "BranchController@edit_department")->name('departments.edit');
            Route::patch('{branch}/departments/{department}', "BranchController@update_department")->name('departments.update');
        });

        Route::prefix('attachments')->group(function () {
            Route::delete('{attachment}', 'AttachmentController@delete');
        });


        Route::get('roles_permissions', 'ProviderController@roles_permissions');

        Route::resources([
            'managers' => 'ManagerController',
            'identities' => 'IdentitiesController']);

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
    });


Route::middleware(['auth'])
    ->namespace("App\Web")
    ->prefix('store')
    ->name('store.')
    ->group(function () {
        Route::prefix('orders')->name('orders.')->group(
            function () {
                Route::get('/', ["OrderController", 'index'])->name('index');
                Route::get('{order}', ["OrderController", 'show'])->name('show');
                Route::get('{order}/accept-order-as-manager', ["OrderController", 'acceptOrderAsManager'])->name('accept-order-as-manager');
                Route::get('{order}/view-payment', ["OrderController", 'viewPayment'])->name('view-payment');
                Route::get('{order}/view-shipping', ["OrderController", 'viewShipping'])->name('view-shipping');
                Route::get('{order}/activities', ["OrderController", 'activites'])->name('activites');
                Route::get('{order}/customer-data', ["OrderController", 'customerData'])->name('customer-data');
            }
        );
        Route::resource('shipping', "ShippingController");
        Route::prefix('/shipping')->name('shipping.')->group(function () {
            Route::post('sign-transactions-to-delivery-man', ["ShippingController", 'signTransactionsToDeliveryMan'])->name('sign-transactions-to-delivery-man');
            Route::post('activate-sign-transactions-to-delivery-man', ["ShippingController", 'activateSignTransactionsToDeliveryMan'])->name('activate-sign-transactions-to-delivery-man');
            Route::prefix('/{shipping}')->group(function () {
                Route::post('delivery_men', ["ShippingController", 'storeDeliveryMan'])->name('delivery_men.store');
                Route::get('view-transactions', ["ShippingController", 'viewTransactions'])->name('view_transactions');
                Route::get('fetch_transactions', ["ShippingController", 'fetchTransactions'])->name('fetch_transactions');
                Route::get('{transaction}/download', ["ShippingController", 'downloadTransaction'])->name('download');
                Route::get('create-transaction', ["ShippingController", 'createTransaction'])->name('create_transaction');
                Route::get('{order}/create-order-transaction', ["ShippingController", 'createOrderTransaction'])->name('create_order_transaction');
                Route::post('store-transaction', ["ShippingController", 'storeTransaction'])->name('store_transaction');
                Route::patch('delivery_men/{deliveryMan}', ["ShippingController", 'updateDeliveryMan'])->name('delivery_men.update');
            });
        });
    });

