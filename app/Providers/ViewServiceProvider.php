<?php
	
	namespace App\Providers;
	
	use App\Http\Views\Composers\ShareLoggedUserComposer;
	use App\Models\Invoice;
	use Illuminate\Contracts\Auth\Guard;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;
	
	class ViewServiceProvider extends ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			
			View::share(
				'organization_config', [
					'vts' => 15,
					'vtp' => 15
				]
			);
			
			
		}
		
		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public function boot(Guard $auth)
		{
			//
			
			Blade::directive(
				'js_asset', function($file) {
				$file = str_replace(['(', ')', "'"], '', $file);
				return '<script src="' . asset($file) . '" ></script>';
			}
			);
			
			Blade::directive(
				'css_asset', function($file) {
				$file = str_replace(['(', ')', "'"], '', $file);
				return '<link href="' . asset($file) . '" rel="stylesheet" />';
			}
			);
			
			
			Blade::directive(
				'defer_js_asset', function($file) {
				$file = str_replace(['(', ')', "'"], '', $file);
				return '<script src="' . asset($file) . '" defer></script>';
			}
			);
			
			
			Blade::directive(
				'meta', function($arguments) {
				
				list($name, $content) = $arguments;
				
				return '<meta name="' . $name . '" content="' . $content . '">';
			}
			);
			
			view()->composer('*', ShareLoggedUserComposer::class);

//        if(auth()->check())
//        {
//        	View::share([
//        		'loggedManager' => auth()->user()
//	        ]);
////	        $reseller = ResellerClosingAccount::where([['is_pending',true],['transaction_type','transfer'],
////		        ['receiver_id',auth()->user()->id]])->with('creator','receiver')->get()
//        }
		
		
		}
		
		
	}
