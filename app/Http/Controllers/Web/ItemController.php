<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Filter\FetchFiltersRequest;
use App\Http\Requests\Web\Item\FetchItemsGroupByCategoryRequest;
use App\Http\Requests\Web\Item\FetchItemsRequest;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{


	private $breadcrumb;



	public function index(FetchItemsGroupByCategoryRequest $fetchItemsGroupByCategoryRequest)
	{

		$categories = [];
		if(!$fetchItemsGroupByCategoryRequest->has('category_id') &&  !$fetchItemsGroupByCategoryRequest->filled('category_id'))
		{
			$categories =  $fetchItemsGroupByCategoryRequest->getData();

		}



		return Inertia::render(
			'Product/Index',
			[
				'search_via' => $fetchItemsGroupByCategoryRequest->input('search_via'),
				'categoryId' => $fetchItemsGroupByCategoryRequest->input('category_id'),
				'name' => $fetchItemsGroupByCategoryRequest->input('name'),
				'categories' => $categories,
			]
		);
	}


	public function search()
	{
		return Inertia::render(
			'Product/Search'
		);
	}
	public function show(Item $itemSlug)
	{

		$relatedItems = $itemSlug->category->items()->with('category', 'filters.filter', 'filters.value')->where('available_qty','>',0)->inRandomOrder()->take(20)->get();

		$this->breadcrumb [] = [
            'title' => trans('store.header.home'),
            "url" => '/web'
        ];

		$this->fillBreadcrumb($itemSlug->category);


		return Inertia::render(
			'Product/Show',
			[
				'item' => $itemSlug->load('filters.filter', 'filters.value', 'category', 'attachments','tags','warrantySubscription'),
				'breadcrumb' => $this->breadcrumb,
				'relatedItems' => $relatedItems,
				'itemUrl' => url('items/'.$itemSlug->id),
                'page_title' => $itemSlug->locale_name
			]
		);
	}
	//

	private function fillBreadcrumb($category)
	{
		if ($category->parent)
			$this->fillBreadcrumb($category->parent);

		$this->breadcrumb[] = [
			'title' => $category->locale_name,
			'url' =>  '/web/categories/' . $category->id

		];
	}
}
