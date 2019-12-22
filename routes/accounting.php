<?php
	
	Auth::routes();
	
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
			'gateways' => 'OrganizationGatewayController',
			'inventories' => 'InventoryController',
			'expenses' => 'ExpenseController',
			'accounts' => 'AccountsController',
			'transactions' => 'TransactionsController',
			'settings' => 'SettingsController',
		]);
		Route::name('items.')->prefix('items')->group(function (){
			Route::get('view/barcode','ItemController@barcode')->name('barcode');
			Route::get('view/serial_activities','ItemController@serial_activities')->name('serial_activities');
			Route::get('{item}/clone','ItemController@clone')->name('clone');
			Route::get('{item}/transactions','ItemController@transactions')->name('transactions');
			Route::name('helper.')->name('helper.')->prefix('helper')->group(function (){
				Route::get('validate_barcode','ItemController@validate_barcode')->name('validate_barcode');
			});
		});
		Route::name('sales.')->prefix('sales')->group(function (){
			Route::get('view/quotations',"SaleController@quotations")->name('quotations');
		});
		Route::prefix('datatable')->group(function (){
			Route::get('items','ItemController@datatable')->name('items.datatable');
			Route::get('filters','FilterController@datatable')->name('filters.datatable');
			Route::get('{filter}/filter_values','FilterValuesController@datatable')->name('filter.values.datatable');
			Route::get('identities','IdentitiesController@datatable')->name('identities.datatable');
			Route::get('managers','ManagerController@datatable')->name('managers.datatable');
			
		});
		Route::prefix('categories')->name('categories.')->group(function (){
			Route::post('view/filters',"ProviderController@categories_filters");
			Route::get('{category}/filters',"CategoryController@filters");
			Route::patch('{category}/filters',"CategoryController@update_filters");
			
		});
		
		Route::get('roles_permissions','ProviderController@roles_permissions');
		
		Route::prefix('dashboard')->name('dashboard.')->group(function (){
			
			Route::get('/','HomeController@index')->name('index');
		});
		
	});