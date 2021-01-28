<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Algolia\ScoutExtended\Facades\Algolia;
use App\ItemTag;
use App\Models\Filter;
use App\Models\Item;

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

		$activeLang = Session::get('webActiveLang', 'ar');
		$langs = ['ar', 'en'];
		if ($request->has('web_active_lang') && $request->filled('web_active_lang') && in_array($request->input('web_active_lang'), $langs)) {
			$activeLang = $request->input('web_active_lang');
			Session::put('webActiveLang', $activeLang);
		}

		app()->setlocale($activeLang);
		$searchKey = Algolia::searchKey(Item::class);


		$searchFilters = [
			// 'online_offer_price',
			// 'category_name',
			// 'category_ar_name',
			// 'filters',
			// 'ar_filters',
		];
		foreach (Filter::all() as $filter) {
			$searchFilters[]  = "filters_{$filter->name}";
			$searchFilters[]  = "ar_filters_{$filter->ar_name}";
		}

		Inertia::share(
			[
				'active_logo' => $activeLang == 'ar' ? asset('images/logo_ar.png') : asset('images/logo_en.png'),
				'active_locale' => app()->getLocale(),
				'client_logged' => auth('client')->check(),
				'client' => auth('client')->user(),
				"app" => config('app'),
				'$t' => __("store"),
				'main_categories' => Category::where('parent_id', 0)->get(),
				'alogria_search_key' => $searchKey,
				'item_tags_search_as' => (new \App\Models\Item())->searchableAs(),
				'alogia_search_filters' => $searchFilters
			]
		);
		return $next($request);
	}
}
