<?php
	
	namespace App\Http\Controllers;
	
	use App\Category;
	use App\Filter;
	use App\Http\Requests\CategoryFiltersRequest;
	use App\Http\Requests\CreateCategoryForm;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class CategoryController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			// $value_obj = FilterValues::find(1017);
			//  $value_obj->setAsLastUsedValue();
			// return Filter::find(17)->values;
			
			// return   $value_obj ;
			
			
			$categories = Category::mainOnly()->with(
				'children.children.children.children.children.children.children.children.children.children.children.children'
			)->get();
			return view('categories.index',compact('categories'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$categories = Category::all();
			$isClone = false;


//        return  $categories;
			return view('categories.create',compact('categories','isClone'));
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateCategoryForm $request)
		{
			//
			
			// return $request->all();
			$request->save();
			return redirect(route('management.categories.index'));
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function show(Category $category)
		{
			//
		}
		
		/**
		 * create with child the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function create_child(Category $category)
		{
			
			$isClone = false;
			$categories = Category::all();
			return view('categories.create',compact('categories','category','isClone'));
			//
		}
		
		/**
		 * clone the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function clone(Category $category)
		{
			
			$categories = Category::all();
			$isClone = true;
			
			
			return view('categories.create',compact('categories','category','isClone'));
			
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function edit(Category $category)
		{
			//
			dd($category);
			$categories = Category::all();
			$filters = Filter::all();
			$category_filters = $category->filters;
			$unused_categorires = $filters->diff($category_filters);
			// return collect( $filters->filter(function($filter) use ($category_filters){
			//     return !in_array($filter->id, $category_filters->pluck('id')->toArray());
			// }) );
			return view('categories.edit',compact('categories','category','category_filters','unused_categorires'));
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function update(Request $request,Category $category)
		{
			
			$category->update($request->except('_token'));
			return redirect(route('management.categories.index'));
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Category $category
		 *
		 * @return Response
		 */
		public function destroy(Category $category)
		{
			
			$category->delete();
			return redirect(route('management.categories.index'));
		}
		
		public function update_filters(Category $category)
		{
			$cat_filters = [];
			$cilfters = $category->filters;
			
//			dd($cilfters);
			foreach ($cilfters as $key => $value){
				$cat_filters[] = collect($value)->forget('pivot');
			}
			
			$all_filters = Filter::whereNotIn("id",collect($cat_filters)->pluck('id')->toArray())->get();
			return view('categories.update_filters',compact('cat_filters','all_filters','category'));
		}
		
		public function store_filters(Category $category,Request $request)
		{
			
			$ids = collect($request->all())->pluck('id');
			
			$category->filters()->detach();
			$category->filters()->attach($ids,
				[
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'sorting' => 0
				]
			);
			
			
		}
		
		/* category filters */
		public function filters(Category $category)
		{
			
			return $category->filters->each(function ($filter){
				$filter['isChecked'] = true;
				$data = [];
				foreach ($filter->values as $value){
					$value['locale_name'] = $value['locale_name'];
					$data[] = $value;
				}
				
				$filter['values'] = $data;
			});
		}
		
		/* category filters */
		public function categories()
		{
			$cats = Category::mainOnly()->with('children.children.children.children.children.children')->get();
			
			return $cats->each(function ($category){
				$category['label'] = $category['name'];
				$category['children']->each(function ($scategory){
					$scategory['label'] = $scategory['name'];
					$scategory['children']->each(function ($sscategory){
						$sscategory['label'] = $sscategory['name'];
						$sscategory['children']->each(function ($ssscategory){
							$ssscategory['label'] = $ssscategory['name'];
						});
					});
				});
			});
		}
	}
