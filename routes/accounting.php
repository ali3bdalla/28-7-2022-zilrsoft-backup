<?php

//
	
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
			'purchases' => 'PurchaseController',
			'kits' => 'KitController',
			'payments' => 'PaymentController',
			'reports' => 'ReportController',
			'branches' => 'BranchController',
			'expenses' => 'ExpenseController',
			'accounts' => 'AccountsController',
			'transactions' => 'TransactionsController',
			'settings' => 'SettingController',
		]);
		
		Route::name('printer.')->prefix('printer')->group(function (){
			Route::get('sign_receipt_printer','PrinterController@sign_receipt_printer');
			Route::get('printers','PrinterController@printers');
			Route::get('print_a4/{invoice}','PrinterController@print_a4')->name('a4');
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
			
		});
		
		
		Route::name('purchases.')->prefix('purchases')->group(function (){
			Route::get('{purchase}/print',"PurchaseController@print")->name('print');
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
			Route::get('purchases','PurchaseController@datatable')->name('purchases.datatable');
			Route::get('sales','SaleController@datatable')->name('sales.datatable');
			
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
			Route::get('/','InventoryController@index')->name('index');
			Route::prefix('beginning')->name('beginning.')->group(function (){
				Route::get('/','InventoryController@beginning_index')->name('index');
				Route::get('/create','InventoryController@beginning_create')->name('create');
				Route::post('/store','InventoryController@beginning_store')->name('store');
				Route::delete('{beginning}','InventoryController@beginning_destroy')->name('destroy');
			});
			
		});
		
		
		Route::prefix('gateways')->name('gateways.')->group(function (){
			Route::get('get_gateways_like_to_manager_name','ProviderController@get_gateways_like_to_manager_name')
				->name('load_manager_gateway');
			
			
		});
		
	});