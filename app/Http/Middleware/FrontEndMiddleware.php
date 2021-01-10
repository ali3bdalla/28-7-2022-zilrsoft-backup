<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	use App\Models\Category;
	class FrontEndMiddleware
	{
		/**
		 * Handle an incoming request.
		 *
		 * @param Request $request
		 * @param Closure $next
		 * @return mixed
		 */
		public function handle($request, Closure $next)
		{
			Inertia::setRootView('web');
			

			app()->setlocale('ar');
			
			Inertia::share(
				[
					'active_locale' => app()->getLocale(),
					'client_logged' => auth('client')->check(),
					'client' => auth('client')->user(),
					"app" => config('app'),
					'$t' => __("store"),
					'main_categories' => Category::where('parent_id',0)->get()
				]
			);
			return $next($request);
		}
	}
