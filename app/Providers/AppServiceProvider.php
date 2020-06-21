<?php
	
	namespace App\Providers;
	
	use App\Components\Observer\InvoiceItems\InvoiceItemsObserver;
	use App\Observers\TransactionObserver;
	use App\InvoiceItems;
	use App\Transaction;
	use Illuminate\Support\ServiceProvider;
	
	class AppServiceProvider extends ServiceProvider
	{
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			
			//
		}
		
		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot()
		{
			
			$this->registerObservers();
			
		}
		
		private function registerObservers()
		{
			InvoiceItems::observe(InvoiceItemsObserver::class);
			Transaction::observe(TransactionObserver::class);
			
		}
	}
