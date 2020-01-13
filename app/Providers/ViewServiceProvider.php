<?php
	
	namespace App\Providers;
	
	use App\ManagerPrivateTransactions;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Pagination\Paginator;
	
	class ViewServiceProvider extends ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			
			$config = [
				'vts' => 5,
				'vtp' => 5
			];
			
			
			
//
			
			
			View::share('organization_config',$config);
			
		}
		
		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public
		function boot()
		{
			//


//			Paginator::defaultView('vendor.pagination.custom.table');
			
		}
	}
