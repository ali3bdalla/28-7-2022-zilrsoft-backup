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

        Route::bind('kit', function ($value) {
            return Item::where([
                ['is_kit', true],
                ['id', $value],
            ])->firstOrFail();
        });

        Route::bind('sale', function ($value) {
            return Invoice::where('id', $value)->withoutGlobalScope('currentManagerInvoicesOnly')->first();
        });

        Route::bind('purchase', function ($value) {
            return Invoice::where('id', $value)->withoutGlobalScope('currentManagerInvoicesOnly')->first();
        });


        Route::bind('inventory', function ($value) {
            return Invoice::where('id', $value)->withoutGlobalScope('currentManagerInvoicesOnly')->first();
        });


        Route::bind('invoice', function ($value) {
            return Invoice::where('id', $value)->withoutGlobalScope('currentManagerInvoicesOnly')->first();
        });
        Route::bind('quotation', function ($value) {
            return Invoice::where([
                ['id', $value],
                ['invoice_type', 'quotation'],
            ])->withoutGlobalScope('currentManagerInvoicesOnly')->first();
        });


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

        $this->mapAccountingRoutes();


        //
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
        Route::middleware('web','auth')
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));

        Route::middleware('web','guest')
        ->namespace($this->namespace)
        ->group(base_path('routes/auth.php'));
    }


    protected function mapApiRoutes()
    {
        Route::middleware('web','auth')
        ->prefix('api')
        ->name('api.')
        ->namespace($this->apiNamespace)
        ->group(base_path('routes/api.php'));
    }


    protected function mapAccountingRoutes()
    {
        Route::middleware('web')
            ->namespace("App\Http\Controllers\Accounting")
            ->prefix("accounting")
            ->name('accounting.')
            ->group(base_path('routes/accounting.php'));
    }
}
