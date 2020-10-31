<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Inertia\Inertia;
	
	class ImagesUploadMiddleware
	{
		
		public function handle($request, Closure $next)
		{
			if($request->session()->has('IMAGE_UPLOAD_PASSWORD') && env('IMAGE_UPLOAD_PASSWORD')) {
				return $next($request);
			}
			return redirect('/images_upload/auth');
		}
	}
