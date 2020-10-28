<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	
	class ManagerGuestMiddleware
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
			if(auth('manager')->check())
				return redirect('/items');
			return $next($request);
			
		}
	}
