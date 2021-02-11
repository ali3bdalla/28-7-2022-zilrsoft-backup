<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $apiNamespace = 'App\Http\Controllers\Api';

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

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {

        $this->mapWebRoutes();
        $this->mapApiRoutes();
        $this->mapBackEndRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));


    }


    protected function mapApiRoutes()
    {
        Route::middleware(['api'])
        ->prefix('mobile')
        ->name('mobile.')
        ->namespace($this->apiNamespace)
        ->group(base_path('routes/mobile.php'));

        Route::middleware(['web'])
            ->prefix('api')
            ->name('api.')
            ->namespace($this->apiNamespace)
            ->group(base_path('routes/api.php'));


        Route::middleware('web', 'guest')
            ->prefix('api')
            ->name('guest.api.')
            ->namespace($this->namespace)
            ->group(base_path('routes/guest_api.php'));
    }


    protected function mapBackEndRoutes()
    {
        Route::middleware('web')
            ->namespace("App\Http\Controllers\Accounting")
            ->prefix("accounting")
            ->name('accounting.')
            ->group(base_path('routes/accounting.php'));

        Route::middleware(['web', "auth"])
            ->prefix('store')
            ->name('store.')
            ->group(base_path('routes/backend/store.php'));
    }


    protected function registerBindings()
    {
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
                ->withoutGlobalScopes(['draft','accountingPeriod',"manager"])->first();
        });
//
        Route::bind('purchase', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_purchase', 'purchase', 'beginning_inventory','inventory_adjustment'])
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
                ->withoutGlobalScopes(['draft','accountingPeriod',"manager"])->first();
        });


        Route::bind('quotation', function ($value) {
            return Invoice::where([
                ['id', $value],
            ])
                ->whereIn('invoice_type', ['return_sale', 'sale'])
                ->withoutGlobalScopes(['draft','accountingPeriod',"manager"])->first();
        });
//
    }
}
