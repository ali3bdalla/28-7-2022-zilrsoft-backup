<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	
	class ClientGuestMiddleware
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
			if(auth('client')->check())
				return redirect('/');
			
			return $next($request);
		}
	}
