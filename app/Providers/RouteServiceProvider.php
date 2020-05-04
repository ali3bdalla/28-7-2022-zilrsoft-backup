<?php

namespace App\Providers;

use App\Invoice;
use App\Item;
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
    //protected $namespace = 'App\Http\Controllers\Management';

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
//

//			Route::bind('sale',function ($value){
//				return Invoice::where('id',$value)->withoutGlobalScope('currentManagerInvoicesOnly')->first();
//			});
////


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
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace("App\Http\Controllers\Api")
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
