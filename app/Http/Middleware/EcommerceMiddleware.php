<?php

namespace App\Http\Middleware;

use App\Models\CartItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class EcommerceMiddleware extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'web';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
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
                'searchable(category_name)', 'searchable(category_ar_name)',
            ])) {
                $searchFilter = str_replace('searchable(', '', $searchFilter);
                $searchFilters[] = str_replace(')', '', $searchFilter);
            }
        }
        return array_merge(parent::share($request), [
            'cart_items' => CartItem::sessionItems(),
            'active_logo' => 'ar' == $activeLang ? asset('images/logo_ar.png') : asset('images/logo_en.png'),
            'active_locale' => app()->getLocale(),
            'client_logged' => auth('client')->check(),
            'client' => auth('client')->user(),
            'app' => config('app'),
            '$t' => __('store'),
            'main_categories' => Category::where('parent_id', 0)->get(),
            'algolia_items_search_as' => 'items_index',
            'aloglia_daily_search_key' => '3b92b3e1e70e7c12777604f891614933',
            'algolia_search_filters' => $searchFilters,
            'algolia_app_key' => 'GM476AOG07',
            'image_processing_url' => config('services.image_processing.url'),
            'categories_search_list' => $this->getSearchCategories($request),
        ]);
    }

    public function getSearchCategories($request): array
    {
        $result = [];
        if ($request->has('category_id') && $request->filled('category_id') && is_int((int)$request->input('category_id'))) {
            $category = Category::where('id', $request->input('category_id'))->first();
            if ($category) {
                $result[] = $category->locale_name;
                $list = $category->getChildrenHashMap();
                if (is_array($list) && '' !== $list[0]) {
                    $categories = Category::find($list);
                    foreach ($categories as $sub) {
                        $result[] = $sub->locale_name;
                    }
                }
            }
        }
        return $result;
    }
}
