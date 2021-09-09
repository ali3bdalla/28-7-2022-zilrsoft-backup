<?php

namespace App\Providers;

use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\Eloquent\AccountsDailyRepository;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\BaseRepositoryContract;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryContract::class, BaseRepository::class);
        $this->app->bind(AccountsDailyRepositoryContract::class, AccountsDailyRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
