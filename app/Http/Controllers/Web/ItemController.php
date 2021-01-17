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


	private $breadcrumb =  [
		[
			'title' => 'الرئيسية',
			"url" => '/web'

		]
	];

	public function index(FetchItemsGroupByCategoryRequest $fetchItemsGroupByCategoryRequest)
	{
		
		$categories = [];
		if(!$fetchItemsGroupByCategoryRequest->has('category_id') &&  !$fetchItemsGroupByCategoryRequest->filled('category_id'))
		{
			$data =  $fetchItemsGroupByCategoryRequest->getData();
			$categories  = $data['categories_group'];
		}


		return Inertia::render(
			'Web/Product/Index',
			[
				'categoryId' => $fetchItemsGroupByCategoryRequest->input('category_id'),
				'name' => $fetchItemsGroupByCategoryRequest->input('name'),
				'categories' => $categories,
			]
		);
	}


	public function show(Item $item)
	{
		$relatedItems = $item->category->items()->with('category')->inRandomOrder()->take(20)->get();



		$this->fillBreadcrumb($item->category);



		return Inertia::render(
			'Web/Product/Show',
			[
				'item' => $item->load('filters.filter', 'filters.value', 'category', 'attachments'),
				'breadcrumb' => $this->breadcrumb,
				'relatedItems' => $relatedItems
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
