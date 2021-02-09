<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ImagesUploadMiddleware
{

	public function handle($request, Closure $next)
	{
		if (($request->session()->has('IMAGE_UPLOAD_PASSWORD') && env('IMAGE_UPLOAD_PASSWORD')) || Auth::check()) {
			return $next($request);
		}
		return redirect('/images_upload/auth');
	}
}
