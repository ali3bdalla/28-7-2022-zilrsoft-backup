<?php

use App\Http\Middleware\ImagesUploadMiddleware;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpClient\HttpClient;

Route::namespace('Store')->name('web.')->middleware('font_end_middleware')->prefix('web')->group(
    function () {
        Route::prefix('items')->name('items.')->group(
            function () {
                Route::match(['POST', 'GET'], '/', 'ItemController@index')->name('index');
                Route::match(['POST', 'GET'], '/using_filters', 'ItemController@usingFilters')->name('using_filters');
            }
        );
        Route::get('shipping_methods', 'ShippingMethodController@index');

        Route::prefix('categories')->name('categories.')->group(
            function () {
                Route::match(['POST', 'GET'], '/', 'CategoryController@index')->name('index');
                Route::get('{category}/subcategories', 'CategoryController@subcategories')->name('subcategories');
            }
        );

        Route::prefix('cart')->name('cart.')->group(
            function () {
                Route::match(['POST', 'GET'], '/get_items_details', 'CartController@getItemDetails')->name('get_items_details');
            }
        );


        Route::prefix('filters')->name('filters.')->group(
            function () {
                Route::match(['POST', 'GET'], '/', 'FilterController@apiGetFilters')->name('get_filters');
            }
        );

        Route::middleware('auth:client')->group(
            function () {
                Route::resource('orders', 'OrderController');
            }
        );

        Route::prefix('{user}')->group(
            function () {
                Route::resource('payment_accounts', 'PaymentAccountController');
            }
        );
    }
);


Route::prefix("upload_images/{item}")->middleware(ImagesUploadMiddleware::class)->group(
    function () {
        Route::get('/', 'ItemController@getImages');
        Route::post('/', 'ItemController@uploadImages');
        Route::post('/update_description', 'ItemController@updateDescription');
        Route::get('/{image}', 'ItemController@deleteImage');
        Route::get('/{image}/set_master', 'ItemController@setMaster');
    }
);


Route::middleware('auth')->group(
    function () {

        Route::resource('orders', 'OrderController');
        Route::prefix("orders/{order}")->name('orders.')->group(
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
        Route::resource('accounts', 'AccountController');
        Route::prefix('accounts')->name('accounts.')->group(
            function () {
                Route::prefix('reports')->name('reports.')->group(
                    function () {
                        Route::get('/{account}', 'AccountController@report')->name('report');
                    }
                );
                Route::prefix('{account}')->group(
                    function () {
                        Route::get('/children', 'AccountController@children')->name('children');
                        Route::get('/entities', 'AccountController@entities')->name('entities');
                    }
                );
            }
        );

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


Route::prefix('app')->group(function () {


    Route::get('/demo_token', function () {
        $token = Str::random(80);

        $manager = Manager::first();
        $manager->update([
            'api_token' => $token
        ]);
        return [
            'demo_token' => $token
        ];
    });
    Route::middleware('api_auth')->group(function () {
        Route::get('token', function (Request $request) {
            $request->validate([
                'expo_token' => 'required|string'
            ]);
            $request->user()->update([
                'expo_token' => $request->input('expo_token')
            ]);
            return [
                'message' => "ok"
            ];
        });

        Route::get('notify', function (Request $request) {
            if ($request->user()->expo_token) {
                $data = [
                    "to" => $request->has('expo_token') && $request->filled('expo_token') ? $request->input('expo_token') : $request->user()->expo_token,
                    "sound" => "default",
                    "title" => "معاملة جديدة",

                    "body" => "قام علي بتحويل 235 من بنك الأهلي الي بنك الراجحي ورقم المعاملة 59185",
                    "data" => [
                        'id' => 1500,
                        'amount' => 325,
                        'sender_name' => "Ali abdalla",
                        "sender_bank_name" => "Rajhi",
                        "sender_bank_account" => "2352312323",
                        "created_at" => Carbon::now(),
                        "destination_bank" => "Test Bank",
                        "destination_bank_account" => "598325325"
                    ]
                ];

                $client = HttpClient::create();
                $response = $client->request(
                    'POST',
                    'https://exp.host/--/api/v2/push/send',
                    [
                        "headers" => [
                            "Accept" => "application/json",
                            "Accept-encoding" => "gzip, deflate",
                            // "Content-Type" => "application/json"
                        ],
                        "body" => $data
                    ]
                );

                return $response->getContent();
            }
        });


        Route::prefix('payments/{payment}')->group(function () {

            Route::post('/', function () {
                return [
                    'message' => 'accepted'
                ];
            });
            Route::delete('/', function () {
                return [
                    'message' => 'rejected'
                ];
            });
        });
    });
});
