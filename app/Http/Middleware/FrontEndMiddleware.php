<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	
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
			
			Inertia::share(
				[
					'client_logged' => auth('client')->check(),
					'client' => auth('client')->user(),
				]
			);
			return $next($request);
		}
	}
