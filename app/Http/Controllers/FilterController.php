<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\Accounting\Filter\CreateFilterRequest;
	use App\Http\Requests\Accounting\Filter\UpdateFilterRequest;
	use App\Models\Filter;
	use App\Models\FilterValues;
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Routing\Redirector;
	use Illuminate\View\View;
	
	class FilterController extends Controller
	{
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Application|Factory|Response|View
		 */
		public function index()
		{
			
			return view('accounting.filters.index');
			
		}
		
		public function create()
		{
			return view('accounting.filters.create');
		}
		
		
		/**
		 * @param Filter $filter
		 *
		 * @return Factory|View
		 */
		public function show(Filter $filter)
		{
			
			return view('accounting.filters.show');
			//
		}
		
		/**
		 * @param Filter $filter
		 *
		 * @return Factory|View
		 */
		public function edit(Filter $filter)
		{
			return view('accounting.filters.edit', compact('filter'));
			//
		}
		
		
	}
