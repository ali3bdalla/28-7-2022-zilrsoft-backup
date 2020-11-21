<?php
	
	use AliAbdalla\PDF\APDFCore;
	use App\Http\Middleware\ImagesUploadMiddleware;
	use Illuminate\Support\Facades\Route;
	use Inertia\Inertia;
	

	
	Route::get('/', 'Web\HomeController@toWeb')->name('to.web');
	
	

	Route::prefix('web')->namespace('Web')->middleware(['font_end_middleware'])->name('web.')->group(
		function() {
			
			Route::middleware('client_guest')->group(
				function() {
					Route::prefix('sign_in')->name('sign_in.')->group(
						function() {
							Route::get('/', 'AuthController@signInPage');
							Route::post('/', 'AuthController@signIn');
						}
					);
					
					Route::prefix('sign_up')->name('sign_up.')->group(
						function() {
							Route::get('/', 'AuthController@signUpPage');
							Route::post('/', 'AuthController@signUp');
							Route::post('/confirm_sign_up', 'AuthController@confirmSignUp');
							Route::get('/confirm_sign_up', 'AuthController@confirmSignUpPage')->name('confirm_sign_up');
						}
					);
					
				}
			);
			
			
			Route::prefix('cart')->name('cart.')->group(
				function() {
					Route::get('/', 'CartController@index');
				}
			);
			
			Route::get('/', 'HomeController@index')->name('index');
			Route::prefix('/categories')->name('categories.')->group(
				function() {
					Route::get('/{category}', 'CategoryController@show')->name('show');
				}
			);
			
			Route::prefix('items')->name('items.')->group(
				function() {
					Route::get('/', 'ItemController@index')->name('index');
					Route::get('/{item}', 'ItemController@show')->name('show');
				}
			);
			
			
			Route::prefix('/items')->name('items.')->group(
				function() {
					Route::get('/{item}', 'ItemController@show')->name('show');
				}
			);
			
		}
	);
	
	
	Route::middleware('manager_guest')->group(
		function() {
			Route::get('/register', 'RegisterController@showRegistrationForm')->name('show_registration_form');
			Route::post('/register', 'RegisterController@register')->name('register');
			Route::post('/login', 'LoginController@login')->name('perform_login');
			Route::get('/login', 'LoginController@showLoginForm')->name('login');
			Route::get('/forget_password', 'LoginController@forgetPassword')->name('forget_password');
		}
	);
	
	
	Route::group(
		['prefix' => 'images_upload'], function() {
		Inertia::setRootView('images_upload');
		
		Route::get('/auth', "ImagesUploadController@auth");
		Route::post('/auth', "ImagesUploadController@grantAuthorization");
		
		Route::group(
			['middleware' => ImagesUploadMiddleware::class], function() {
			Route::get('/', "ImagesUploadController@index");
			Route::get('/redirect', "ImagesUploadController@redirectInertia");
			Route::get('/{item}', "ImagesUploadController@show");
		}
		);
		
	}
	);
	
	
	Route::middleware('auth')->group(
		function() {
			
			Route::resource('orders', 'OrderController');
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
							Route::get('/{sale}/to_invoice', 'SaleController@toInvoice')->name('to_invoice');
						}
					);
				}
			);
			Route::resource('accounts', 'AccountController');
			Route::prefix('accounts')->name('accounts.')->group(
				function() {
					Route::prefix('reports')->name('reports.')->group(
						function() {
							Route::get('/index', 'AccountController@reports')->name('index');
						}
					);
					Route::prefix('{account}')->name('show.')->group(
						function() {
//							Route::get('/stock', 'AccountController@showStock')->name('stock');
							Route::get('/stock/{item}', 'AccountController@showItem')->name('item');
							Route::get('/identity/{identity}', 'AccountController@showIdentity')->name('identity');
							
						}
					);
					
				}
			);
			Route::resource('vouchers', 'VoucherController');
			Route::prefix('vouchers/manual')->name('vouchers.')->group(
				function() {
					Route::get('/create-supplier', "VoucherController@createSupplierVoucher")->name('create.supplier');
				}
			);
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
									Route::get('/{transaction}/delete_transaction', 'DailyController@deleteResellerAccountTransaction')->name('confirm');
								}
							);
						}
					);
					
				}
			);
			
			
			Route::prefix('filters')->name('filter')->group(
				function() {
					Route::get('/', 'FilterController@index')->name('index');
					Route::get('/create', 'FilterController@create')->name('create');
					Route::get('/{filter}', 'FilterController@show')->name('show');
					Route::get('/{filter}/edit', 'FilterController@edit')->name('edit');
					
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
