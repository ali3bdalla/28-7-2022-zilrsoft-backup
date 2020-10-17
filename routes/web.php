<?php
	
	use Illuminate\Support\Facades\Route;
	
	Route::middleware('guest')->group(
		function() {
			Route::get('/', 'GuestController@index')->name('index');
			Route::get('/register', 'RegisterController@showRegistrationForm')->name('show_registration_form');
			Route::post('/register', 'RegisterController@register')->name('register');
			Route::post('/login', 'LoginController@login')->name('perform_login');
			Route::get('/login', 'LoginController@showLoginForm')->name('login');
			Route::get('/forget_password', 'LoginController@forgetPassword')->name('forget_password');
		}
	);
	Route::middleware('auth')->group(
		function() {
			Route::get('/dashboard', 'HomeController@index')->name('dashboard.index');
			Route::post('/logout', 'HomeController@logout')->name('logout');
			Route::resource('sales', 'SaleController');
			Route::prefix('sales')->name('sales.')->group(
				function() {
					Route::prefix('drafts')->name('drafts.')->group(
						function() {
							Route::get('/index', 'SaleController@drafts')->name('index');
							Route::get('/create', 'SaleController@createDraft')->name('create');
							Route::get('/create_service', 'SaleController@createServiceDraft')->name('create.service');
							Route::get('/{sale}/clone', 'SaleController@clone')->name('clone');
						}
					);
				}
			);
			Route::resource('accounts', 'AccountController');
			Route::prefix('accounts/{account}/view')->name('accounts.show.')->group(
				function() {
					Route::get('/stock', 'AccountController@showStock')->name('stock');
					Route::get('/{item}/item', 'AccountController@showItem')->name('item');
					
				}
			);
			Route::resource('vouchers', 'VoucherController');
			Route::resource('entities', 'EntityController');
			Route::prefix('entities')->name('entities.')->group(
				function() {
					Route::get('user/{account}/{user}', "EntityController@showUserEntities")->name('user');
				}
			);
			Route::prefix('financial_statements')->name('financial_statements.')->group(
				function() {
					Route::get('/', 'FinancialStatementController@index')->name('index');
					Route::get('trial_balance', 'FinancialStatementController@trailBalance')->name('trial_balance');
				}
			);
			Route::resource('items', 'ItemController');
			Route::prefix('items/{item}')->name('items.')->group(
				function() {
					Route::get('/transactions', 'ItemController@transactions')->name('transactions');
					Route::get('/view_serials', 'ItemController@serials')->name('serials');
					Route::get('/clone', 'ItemController@clone')->name('clone');
				}
			);
			Route::resource('purchases', 'PurchaseController');
			Route::prefix('purchases')->name('purchases.')->group(
				function() {
					Route::get('view/drafts', 'PurchaseController@drafts')->name('drafts');
				}
			);
			
			Route::resource('entities', 'EntityController');
			Route::prefix('inventory')->name('inventory.')->group(
				function() {
					Route::get('/', 'InventoryController@index')->name('index');
					Route::get('/create', 'InventoryController@create')->name('create');
				}
			);
			Route::prefix('daily')->name('daily.')->group(
				function() {
					Route::get('/', 'DailyController@begning')->name('index');
					Route::prefix('reseller')->name('reseller.')->group(
						function() {
							Route::prefix('closing_accounts')->name('closing_accounts.')->group(
								function() {
									Route::get('/', 'DailyController@resellerClosingAccountsIndex')->name('index');
									Route::get('/create', 'DailyController@createResellerClosingAccount')->name('create');
								}
							);
							Route::prefix('accounts_transactions')->name('accounts_transactions.')->group(
								function() {
									Route::get('/', 'DailyController@resellerAccountsTransactionsIndex')->name('index');
									Route::get('/create', 'DailyController@createResellerAccountTransaction')->name('create');
									Route::get('/{transaction}/confirm', 'DailyController@confirmResellerAccountTransaction')->name('confirm');
								}
							);
						}
					);
					
				}
			);
			Route::prefix('/accounting')->name('accounting.')->namespace('Accounting')->group(
				function() {
					Route::resources(
						[
							'expenses' => 'ExpenseController'
						]
					);
					Route::prefix('/datatable')->group(
						function() {
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
			
		}
	);
