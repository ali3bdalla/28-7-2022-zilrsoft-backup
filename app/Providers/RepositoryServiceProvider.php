<?php

namespace App\Providers;

use App\Repository\AccountRepositoryContract;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\BaseRepositoryContract;
use App\Repository\Eloquent\AccountRepository;
use App\Repository\Eloquent\AccountsDailyRepository;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\EntryRepository;
use App\Repository\Eloquent\InvoiceRepository;
use App\Repository\Eloquent\ManagerDailyWalletRepository;
use App\Repository\Eloquent\ManagerRepository;
use App\Repository\Eloquent\OrderRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\VoucherRepository;
use App\Repository\EntryRepositoryContract;
use App\Repository\InvoiceRepositoryContract;
use App\Repository\ManagerDailyWalletRepositoryContract;
use App\Repository\ManagerRepositoryContract;
use App\Repository\OrderRepositoryContract;
use App\Repository\UserRepositoryContract;
use App\Repository\VoucherRepositoryContract;
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
        $this->app->bind(ManagerRepositoryContract::class, ManagerRepository::class);
        $this->app->bind(AccountRepositoryContract::class, AccountRepository::class);
        $this->app->bind(InvoiceRepositoryContract::class, InvoiceRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(OrderRepositoryContract::class, OrderRepository::class);
        $this->app->bind(ManagerDailyWalletRepositoryContract::class, ManagerDailyWalletRepository::class);
        $this->app->bind(EntryRepositoryContract::class, EntryRepository::class);
        $this->app->bind(VoucherRepositoryContract::class, VoucherRepository::class);
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
