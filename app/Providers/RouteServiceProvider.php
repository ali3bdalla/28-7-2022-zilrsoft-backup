<?php

namespace App\Providers;

use App\Http\Middleware\QuickBooksAuthenticationMiddleware;
use App\Models\Invoice;
use App\Models\Item;
use App\Scopes\DraftScope;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    const HOME = "dashboard";
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $apiNamespace = 'App\Http\Controllers\App\API';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->registerBindings();
    }

    protected function registerBindings()
    {
        Route::bind('itemSlug', function ($value) {
            return Item::where('slug', (string)$value)->firstOrFail();
        });
        Route::bind('kit', function ($value) {
            return Item::whereIsKit(true)->whereId($value)->firstOrFail();
        });
        Route::bind('sale', function ($value) {
            return Invoice::whereId($value)
                ->whereIn('invoice_type', ['return_sale', 'sale'])
                ->withoutGlobalScopes([DraftScope::class])->first();
        });
        Route::bind('purchase', function ($value) {
            return Invoice::whereId($value)
                ->whereIn('invoice_type', ['return_purchase', 'purchase', 'beginning_inventory', 'inventory_adjustment'])->first();
        });
        Route::bind('returnPurchase', function ($value) {
            return Invoice::whereId($value)->whereInvoiceType('return_purchase')->first();
        });
        Route::bind('invoice', function ($value) {
            return Invoice::whereId($value)->withoutGlobalScopes([DraftScope::class])->first();
        });
        Route::bind('quotation', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_sale', 'sale'])
                ->withoutGlobalScopes([DraftScope::class])->first();
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapStoreRoutes();
        $this->mapAppRoutes();
    }

    protected function mapStoreRoutes()
    {
        Route::group(['middleware' => ["web", "ecommerceMiddleware"], 'as' => 'web.', 'namespace' => "App\Http\Controllers\Store\Web", 'prefix' => "web"], base_path('store-routes/web.php'));
        Route::group(['middleware' => ['web', 'ecommerceMiddleware'], 'prefix' => 'api/web', 'as' => 'api.web.', 'namespace' => '\App\Http\Controllers\Store\API'], base_path('store-routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAppRoutes()
    {
        Route::redirect('/', '/web');
        Route::middleware(['web'])
            ->group(base_path('routes/guest.php'));

        Route::middleware(['web', 'quickbooks','auth'])
            ->prefix("quickbooks")
            ->group(base_path('routes/quickbooks.php'));


        Route::middleware(['web', 'auth',QuickBooksAuthenticationMiddleware::class])
            ->group(base_path('routes/web.php'));

        Route::middleware(['web'])
            ->prefix('api')
            ->name('api.')
            ->namespace("$this->apiNamespace")
            ->group(base_path('routes/api.php'));
    }
}
