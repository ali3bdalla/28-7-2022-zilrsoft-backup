<?php

//
	
	
	use App\Events\TestBroadcastEvent;
	
	event(new TestBroadcastEvent());

//	auth()->loginUsingId(8);
	app()->setLocale('ar');
	Auth::routes(["verify" => true]);
	
	
	Route::name('printer')->prefix('printer')->group(function (){
		Route::get('print_receipt/{sale}','PrinterController@print_receipt');
	});
	
	
	Route::get('/','HomeController@index');
	Route::middleware(['auth','verified'])->group(function (){
		Route::resources([
			'managers' => 'ManagerController',
			'identities' => 'IdentitiesController',
			'categories' => 'CategoryController',
			'filters' => 'FilterController',
			'filter_values' => 'FilterValuesController',
			'items' => 'ItemController',
			'sales' => 'SaleController',
			'quotations' => 'QuotationController',
			'purchases' => 'PurchaseController',
			'kits' => 'KitController',
			'vouchers' => 'VoucherController',
			'reports' => 'ReportController',
			'branches' => 'BranchController',
			'expenses' => 'ExpenseController',
			'accounts' => 'ChartsController',
			'transactions' => 'TransactionsController',
			'settings' => 'SettingController',
			'warranty_subscriptions' => 'WarrantySubscriptionsController',
		]);
		
		
		Route::prefix('accounts')->name('accounts.')->group(function (){
			Route::get('load_children/{account}/list',"ChartsController@load_children")->name('load_children');
			Route::get('client/{client}/{account}',"ChartsController@client")->name('client');
			Route::get('vendor/{vendor}/{account}',"ChartsController@vendor")->name('vendor');
			Route::get('item/{item}/{account}',"ChartsController@item")->name('item');
			Route::get('{account}/delete',"ChartsController@delete");
			Route::get('{account}/transactions_datatable',"ChartsController@transactions_datatable")->name('transactions_datatable');
			Route::prefix('reports')->name('reports.')->group(function(){
				Route::get('index',"ChartsController@reports")->name('index');
				Route::get('{account}/result',"ChartsController@reports_result")->name('result');
			});
		});
		Route::prefix('reseller_daily')->name('reseller_daily.')->group(function (){
			Route::get('account_close',"ResellerDailyTransactions@account_close")->name('account_close');
			Route::post('account_close',"ResellerDailyTransactions@account_close_store")->name('account_close_store');
			Route::get('account_close_list',"ResellerDailyTransactions@account_close_list")->name('account_close_list');
			Route::get('transfer_list',"ResellerDailyTransactions@transfer_list")->name('transfer_list');
			Route::get('transfer_amounts',"ResellerDailyTransactions@transfer_amounts")->name('transfer_amounts');
			Route::post('transfer_amounts',"ResellerDailyTransactions@transfer_amounts_store")->name('transfer_amounts_store');
			Route::get('{transaction}/confirm_transaction',"ResellerDailyTransactions@confirm_transaction")->name
			('confirm_transaction');
			
			Route::get('{transaction}/delete_transaction',"ResellerDailyTransactions@delete_transaction")->name
			('delete_transaction');
		});
		
		Route::prefix('transactions')->name('transactions.')->group(function (){
			Route::get('add/create',"TransactionsController@create")->name('add.create');
			
		});
		
		
		Route::name('printer.')->prefix('printer')->group(function (){
			Route::get('sign_receipt_printer','PrinterController@sign_receipt_printer');
			Route::get('printers','PrinterController@printers');
			Route::get('print_a4/{invoice}','PrinterController@print_a4')->name('a4');
			Route::get('voucher/{voucher}','PrinterController@voucher')->name('voucher');
		});
		
		Route::name('items.')->prefix('items')->group(function (){
			Route::get('view/barcode','ItemController@barcode')->name('barcode');
			Route::get('view/show_item_barcode','ItemController@show_item_barcode')->name('show_item_barcode');
			Route::get('view/serial_activities','ItemController@serial_activities')->name('serial_activities');
			Route::get('view/show_serial_activities','ItemController@show_serial_activities')->name('show_serial_activities');
			Route::get('{item}/clone','ItemController@clone')->name('clone');
			Route::get('{item}/transactions','ItemController@transactions')->name('transactions');
			Route::get('{item}/transactions_datatable','ItemController@transactions_datatable')->name('transactions_datatable');
			Route::get('{item}/view_serials','ItemController@view_serials')->name('view_serials');
			Route::name('helper.')->name('helper.')->prefix('helper')->group(function (){
				Route::match(['get','post'],'validate_barcode','ItemController@validate_barcode')->name
				('validate_barcode');
				Route::post('activate_items','ItemController@activate_items')->name('activate_items');
				Route::match(['get','post'],'query_find_items','ProviderController@query_find_items')->name
				('query_find_items');
				
				Route::match(['get','post'],'query_validate_purchase_serial','ProviderController@query_validate_purchase_serial')->name
				('query_validate_purchase_serial');
				Route::match(['get','post'],'query_validate_sale_serial','ProviderController@query_validate_sale_serial')->name
				('query_validate_purchase_serial');
				Route::match(['get','post'],'query_validate_return_sale_serial','ProviderController@query_validate_return_sale_serial')->name
				('query_validate_purchase_serial');
				Route::match(['get','post'],'query_validate_return_purchase_serial','ProviderController@query_validate_return_purchase_serial')->name
				('query_validate_purchase_serial');
				
			});
		});
		
		Route::name('kits.')->prefix('kits')->group(function (){
			Route::name('helper.')->name('helper.')->prefix('helper')->group(function (){
				Route::get('get_kit_amounts/{kit}','ProviderController@get_kit_amounts')->name('get_kit_amounts');
				
			});
		});
		
		
		Route::name('sales.')->prefix('sales')->group(function (){
			Route::get('view/quotations',"SaleController@quotations")->name('quotations');
			Route::get('{sale}/print',"SaleController@print")->name('print');
			Route::get('{beginning}/force_delete',"InventoryController@delete_sale")->name('delete');
			Route::get('{beginning}/return_force_delete',"InventoryController@delete_return_sale")->name('delete_return');
		});
		
		
		Route::name('quotations.')->prefix('quotations')->group(function (){
			Route::get('create/service',"QuotationController@quotations")->name('services');
			Route::get('services_quotations/index',"QuotationController@services_quotations")->name('services_quotations');
		});
		
		
		Route::name('purchases.')->prefix('purchases')->group(function (){
			Route::get('{purchase}/print',"PurchaseController@print")->name('print');
			Route::get('{beginning}/force_delete',"InventoryController@delete_purchase")->name('delete');
			Route::get('{purchase}/clone',"PurchaseController@clone")->name('clone');
			Route::get('pending/list',"PurchaseController@pending_list")->name('pending');
		});
		
		
		Route::prefix('datatable')->group(function (){
			Route::get('items','ItemController@datatable')->name('items.datatable');
			Route::get('filters','FilterController@datatable')->name('filters.datatable');
			Route::get('{filter}/filter_values','FilterValuesController@datatable')->name('filter.values.datatable');
			Route::get('identities','IdentitiesController@datatable')->name('identities.datatable');
			Route::get('managers','ManagerController@datatable')->name('managers.datatable');
			Route::get('branches','BranchController@datatable')->name('branches.datatable');
			Route::get('branches/{branch}/departments','BranchController@departments_datatable')->name('branches.datatable');
			Route::get('beginning_inventories','InventoryController@beginning_datatable')->name('beginning.datatable');
			Route::get('adjust_stock_inventories','AdjustStockController@datatable')->name('adjust_stock.datatable');
			Route::get('purchases','PurchaseController@datatable')->name('purchases.datatable');
			Route::get('sales','SaleController@datatable')->name('sales.datatable');
			Route::get('vouchers','VoucherController@datatable')->name('vouchers.datatable');
			
		});
		Route::prefix('categories')->name('categories.')->group(function (){
			Route::post('view/filters',"ProviderController@categories_filters");
			Route::get('{category}/filters',"CategoryController@filters");
			Route::patch('{category}/filters',"CategoryController@update_filters");
			Route::get('{category}/clone',"CategoryController@clone");
			
		});
		Route::prefix('branches')->name('branches.')->group(function (){
			Route::get('{branch}/departments',"BranchController@departments")->name('departments.index');
			Route::get('{branch}/departments/create',"BranchController@create_department")->name('departments.create');
			Route::post('{branch}/departments',"BranchController@store_department")->name('departments.store');
			Route::delete('{branch}/departments/{department}',"BranchController@destroy_department")->name('departments.delete');
			Route::get('{branch}/departments/{department}/edit',"BranchController@edit_department")->name('departments.edit');
			Route::patch('{branch}/departments/{department}',"BranchController@update_department")->name('departments.update');
			
			
		});
		Route::get('roles_permissions','ProviderController@roles_permissions');
		Route::prefix('dashboard')->name('dashboard.')->group(function (){
			Route::get('/','HomeController@index')->name('index');
		});
		
		Route::prefix('inventories')->name('inventories.')->group(function (){
			Route::resources([
				'adjust_stock' => 'AdjustStockController',
			]);
			Route::get('inventory_reconciliation/index','AdjustStockController@inventory_reconciliation')->name('inventory_reconciliation');
			Route::get('/','InventoryController@index')->name('index');
			Route::prefix('beginning')->name('beginning.')->group(function (){
				Route::get('/','InventoryController@beginning_index')->name('index');
				Route::get('/create','InventoryController@beginning_create')->name('create');
//				Route::get('{beginning}/edit','InventoryController@beginning_edit')->name('edit');
				Route::post('/store','InventoryController@beginning_store')->name('store');
				Route::delete('{beginning}','InventoryController@beginning_destroy')->name('destroy');
				Route::get('{beginning}/force_delete','InventoryController@beginning_destroy')->name('delete');
				Route::put('{beginning}','InventoryController@beginning_return')->name('return');
			});
			
		});
		
		
		Route::prefix('gateways')->name('gateways.')->group(function (){
			Route::get('get_gateways_like_to_manager_name','ProviderController@get_gateways_like_to_manager_name')
				->name('load_manager_gateway');
			
		});
		
	});