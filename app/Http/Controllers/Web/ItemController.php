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
			'Web/Product/Index',
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
			'Web/Product/Search'
		);
	}
	public function show(Item $item)
	{

		$relatedItems = $item->category->items()->with('category')->where('available_qty','>',0)->inRandomOrder()->take(20)->get();

		$this->breadcrumb [] = [
            'title' => trans('store.header.home'),
            "url" => '/web'
        ];

		$this->fillBreadcrumb($item->category);


		return Inertia::render(
			'Web/Product/Show',
			[
				'item' => $item->load('filters.filter', 'filters.value', 'category', 'attachments','tags','warrantySubscription'),
				'breadcrumb' => $this->breadcrumb,
				'relatedItems' => $relatedItems,
				'itemUrl' => url('web/items/'.$item->id)
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
