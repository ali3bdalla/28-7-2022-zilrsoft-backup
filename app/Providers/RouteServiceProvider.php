<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller app-routes.
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
        //

        parent::boot();

        $this->registerBindings();
    }

    protected function registerBindings()
    {
        Route::bind('itemSlug', function ($value) {
            $query = Item::where('en_slug', $value)->orWhere('ar_slug', $value);
            if (is_numeric($value)) {
                $query = $query->orWhere('id', $value);
            }


            return $query->firstOrFail();
        });
        Route::bind('kit', function ($value) {
            return Item::where([
                ['is_kit', true],
                ['id', $value],
            ])->firstOrFail();
        });

        Route::bind('sale', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_sale', 'sale'])
                ->withoutGlobalScopes(['draft', 'accountingPeriod', 'manager'])->first();
        });
//
        Route::bind('purchase', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_purchase', 'purchase', 'beginning_inventory', 'inventory_adjustment'])
                ->withoutGlobalScope('manager')->first();
        });

        Route::bind('returnPurchase', function ($value) {
            return Invoice::where([
                ['id', $value],
                ['invoice_type', 'return_purchase']
            ])->withoutGlobalScope('manager')->first();
        });

        Route::bind('invoice', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->withoutGlobalScopes(['draft', 'accountingPeriod', 'manager'])->first();
        });

        Route::bind('quotation', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_sale', 'sale'])
                ->withoutGlobalScopes(['draft', 'accountingPeriod', 'manager'])->first();
        });
//
    }

    /**
     * Define the app-routes for the application.
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
        Route::middleware('web')
            ->namespace('App\Http\Controllers\Store')
            ->group(base_path('store-routes/web.php'));

        Route::middleware('web')
            ->namespace('\App\Http\Controllers\Store\API')
            ->as('api.web.')
            ->middleware('ecommerceMiddleware')
            ->prefix('api/web')
            ->group(base_path('store-routes/api.php'));
    }

    /**
     * Define the "web" app-routes for the application.
     *
     * These app-routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAppRoutes()
    {

        Route::middleware(['guest', 'web', 'guest:manager'])
            ->namespace("$this->namespace\App\Auth")
            ->group(base_path('app-routes/guest.php'));
        Route::middleware(['web', 'auth'])
            ->namespace("\App\Http\Controllers")
            ->group(base_path('app-routes/web.php'));

        Route::middleware('web')
            ->prefix('api')
            ->name('api.')
            ->namespace($this->apiNamespace)
            ->group(base_path('app-routes/api.php'));


    }

}
