<?php
	
	app()->setLocale('ar');
	
	Auth::routes(['verify' => true]);
	
	
	
	Route::prefix('printer')->name('printers.')->group(function (){
		Route::get('sale_receipt/{invoice}','PrinterController@sale_receipt');
		Route::get('barcode/{item}','PrinterController@barcode');
		Route::get('a4/{invoice}','PrinterController@a4');
		Route::get('sign','PrinterController@sign');
		Route::get('a4/voucher/{voucher}','PrinterController@voucher')->name('voucher');
	});
	
	
	Route::middleware(['auth','verified'])->group(function (){
		
		Route::get('/dashboard',"HomeController@index")->name('dashboard')->middleware('role:view-dashboard');
		Route::get('/dashboard',"HomeController@index")->name('dashboard')->middleware('role:view-dashboard');
		
		
		Route::resources([
			'managers' => 'ManagerController',
			'users' => 'UsersController',
			'roles' => 'RoleController',
			'vendors' => 'VendorController',
			'clients' => 'ClientController',
			'categories' => 'CategoryController',
			'filters' => 'FilterController',
			'items' => 'ItemController',
			'sales' => 'SaleController',
			'purchases' => 'PurchaseController',
			'kits' => 'KitController',
			'payments' => 'PaymentController',
			'reports' => 'ReportController',
			'branches' => 'BranchController',
			'gateways' => 'OrganizationGatewayController',
			'inventories' => 'InventoryController',
			'expenses' => 'ExpenseController',
			'charts' => 'ChartController',
		
		]);


		Route::prefix('chart')->name('charts.')->group(function(){
		   Route::get('item/{item}/{chart}',"ChartController@item")->name('item');
        });
		
		Route::prefix('sales')->name('sales.')->group(function (){
			Route::get('/list/unpaid/{user}/list',"SaleController@unpaid")->name('unpaid');
			Route::get('{invoice}/clone',"SaleController@clone")->name('clone');
			
			Route::post('/table/fetch',"SaleController@table")->name('table');
			
			
			Route::get('/list/unpaid/all',"SaleController@unpaid_all")->name('unpaid_all');
			
		});
		
		Route::prefix('purchases')->name('purchases.')->group(function (){
			Route::get('{invoice}/clone',"PurchaseController@clone")->name('clone');
			Route::get('/list/unpaid/{user}/list',"PurchaseController@unpaid")->name('unpaid');
			Route::get('/list/unpaid/all',"PurchaseController@unpaid_all")->name('unpaid_all');
			
		});
		
		
		Route::prefix('inventories')->name('inventories.beginning.')->group(function (){
			Route::get('/beginning/index',"InventoryController@beginning")->name('index');
			Route::post('/beginning',"InventoryController@beginning_store")->name('store');
			Route::get('/beginning/create',"InventoryController@beginning_create")->name('create');
			Route::get('/beginning/{inventory}/edit',"InventoryController@beginng_edit")->name('edit');
			Route::patch('/beginning/{inventory}/update',"InventoryController@beginng_update")->name('update');
		});
		
		Route::prefix('users')->name('users.')->group(function (){
			//signout
			Route::get('/auth/signout',"UsersController@signout")->name('signout');
			Route::get('{user}/{payWay}/accounts',"UsersController@get_ways_with_accounts_that_user_has_account_on_them");
			Route::get('{user}/update_payments_accounts',"UsersController@update_payments_accounts")->name('update_payments_accounts');
			Route::get('{user}/create_payments_accounts',"UsersController@create_payments_accounts")->name('create_payments_accounts');
			Route::post('{user}/store_payments_accounts',"UsersController@store_payments_accounts");
		});
		
		
		// categories pluings routes
		Route::prefix('categories')->name('categories.')->group(function (){
			Route::get('/create/{category}',"CategoryController@create_child");
			Route::get('/clone/{category}',"CategoryController@clone");
			Route::get('/filters/{category}',"CategoryController@filters");
			Route::get('/categories/list/update',"CategoryController@categories");
			Route::get('/{category}/filters',"CategoryController@update_filters")->name('filters');
			Route::patch('/{category}/filters',"CategoryController@store_filters");
		});
		
		
		Route::prefix('items')->name('items.')->group(function (){
			Route::get('{item}/movement','ItemController@movement');
			Route::post('check_barcode_if_exists',"ItemController@checkBarcodeIfItAlreadyUsed");
			Route::patch('/store/save',"ItemController@store");
			Route::put('{item}/store/update',"ItemController@update");
			Route::get('{item}/delete',"ItemController@destroy");
			Route::get('{item}/activate',"ItemController@activate");
			Route::get('flow/{item}/list',"ItemController@show2");
			Route::get('/table/list',"ItemController@table");
			Route::match(['get','post'],'/datatable/index',"ItemController@datatable_list");
			Route::get('/search/barcode/{barcode}',"ItemController@findItems");
			Route::get('/pending/list',"ItemController@pending")->name('pending');
			Route::get('/clone/{item}',"ItemController@clone")->name('clone');
			Route::get('/serials/check',"ItemController@checkserial");
			Route::get('/serials/available',"ItemController@checkserialavailable");
			Route::get('/serials/checkserialforsale',"ItemController@checkserialforsale");
			Route::prefix('barcode')->name('barcode.')->group(function (){
				Route::get('/index',"ItemController@barcode")->name('index');
				Route::get('/show',"ItemController@barcode_show")->name('show');
			});
			
			
		});
		
		
		// items pluings routes
		Route::prefix('serial_history')->name('serial_history.')->group(function (){
			Route::get('/',"SerialHistoryController@index")->name('index');
			Route::get('/show',"SerialHistoryController@show")->name('show');
			
			
		});
		
		
		// items pluings routes
		Route::prefix('filters')->name('filters.')->group(function (){
			Route::post('/values/create',"FilterController@create_value");
			Route::patch('/values/update',"FilterController@upate_value");
			Route::delete('/values/delete/{value}',"FilterController@delete_value");
			
		});
		
		// items pluings routes
		Route::prefix('gateways')->name('gateways.')->group(function (){
			Route::get('/{payWay}/remove',"OrganizationGatewayController@destroy")->name('remove');
			
			Route::get('/{payWay}/children',"OrganizationGatewayController@children")->name('children');
			Route::get('/{payWay}/load_children',"OrganizationGatewayController@loadChildren")->name('load_children');
			Route::get('/{payWay}/create',"OrganizationGatewayController@create_child")->name('create_child');
			Route::get('fetch/all',"OrganizationGatewayController@fetch");
			
		});
		
		
		Route::prefix('payments')->name('payments.')->group(function (){
			Route::get('/payments/index',"PaymentController@payments")->name('payments');
			Route::get('/print/{payment}',"PaymentController@print")->name('print');
			Route::get('/receipts/index',"PaymentController@receipts")->name('receipts');
			Route::get('/create/create_receipt',"PaymentController@create_receipt")->name('create_receipt');
			Route::get('/create/create_payment',"PaymentController@create_payment")->name('create_payment');
			Route::post('/create/store_receipt',"PaymentController@store_receipt")->name('store_receipt');
			Route::post('/create/store_payment',"PaymentController@store_payment")->name('store_payment');
			
		});
		
		
		Route::prefix('settings')->name('settings.')->group(function (){
			Route::get('/index',"SettingsController@index")->name('index');
			Route::get('/payment_accounts',"SettingsController@payment_accounts")->name('payment_accounts');
			Route::get('/payments_account_create',"SettingsController@payments_account_create")->name('payments_account_create');
			Route::post('/payments_account_store',"SettingsController@payments_account_store")->name('payments_account_store');
			Route::prefix('global')->name('global.')->group(function (){
				Route::get('/local_printers',"SettingsController@local_printers")->name('printers');
				
			});
			
		});
		
		
		Route::prefix('organizations')->name('organizations.')->group(function (){
			Route::get('/{payWay}/accounts',"OrganizationController@get_ways_with_accounts_that_organization_has_account_on_them");
			
		});
		
		
		
		
		Route::name('accounting.')->namespace("Accounting")->group(function (){
			Route::resources([
				'chart_accounts' => 'ChartAccountsController'
			]);
			
			Route::prefix('accounting')->group(function (){
				Route::get('/gateways/{gateway}/{account}',"ChartAccountsController@gateway");
			});
			
			
		});
		
	});
