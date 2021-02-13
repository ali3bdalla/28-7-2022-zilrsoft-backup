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


		$itemsIndexSearchFilters = config('scout-items-index.attributesForFaceting');

		$searchFilters = [];
		foreach ($itemsIndexSearchFilters as $searchFilter) {
			if (!in_array($searchFilter, [
				'searchable(online_offer_price)',
				'searchable(category_name)', 'searchable(category_ar_name)'
			])) {
				$searchFilter = str_replace('searchable(', "", $searchFilter);
				$searchFilters[] = str_replace(')', "", $searchFilter);
			}
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
				'algolia_items_search_as' => "items_index",
				'aloglia_daily_search_key' => "3b92b3e1e70e7c12777604f891614933",
				'algolia_search_filters' => $searchFilters,
				'algolia_app_key' => "GM476AOG07",
				'image_processing_url' => config('services.image_processing.url'),
				'categories_search_list' => $this->getSearchCategories($request),
			]
		);
		return $next($request);
	}

	public function getSearchCategories($request)
	{
		$result  = [];

		if ($request->has('category_id') && $request->filled('category_id') && is_int((int)$request->input('category_id'))) {
			$category = Category::where('id', $request->input('category_id'))->first();
			if ($category) {
				$result[] = $category->locale_name;
				$categories = Category::find($category->getChildrenHashMap());
				foreach ($categories as $key => $sub) {
					$result[] = $sub->locale_name;
				}
			}
		}



		return $result;
	}
}
