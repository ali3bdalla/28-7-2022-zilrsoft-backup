<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	use App\Models\Category;
use Illuminate\Support\Facades\Session;

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
			
			$activeLang = Session::get('webActiveLang','ar');
			$langs = ['ar','en'];
			if($request->has('web_active_lang') && $request->filled('web_active_lang') && in_array($request->input('web_active_lang'),$langs ))
			{
				$activeLang = $request->input('web_active_lang');
				Session::put('webActiveLang',$activeLang);
			}
			
			app()->setlocale($activeLang);
			
			Inertia::share(
				[
					'active_logo' => $activeLang == 'ar' ? asset('images/logo_ar.png'): asset('images/logo_en.png'), 
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
