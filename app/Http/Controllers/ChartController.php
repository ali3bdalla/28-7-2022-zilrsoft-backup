<?php
	
	namespace App\Http\Controllers;
	
	use App\Chart;
	use App\Http\Controllers\ControllersHelper\ChartControllerGatewayHelper;
	use App\Http\Controllers\ControllersHelper\ChartControllerStockHelper;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class ChartController extends Controller
	{
		
		use ChartControllerGatewayHelper,ChartControllerStockHelper;
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$charts = Chart::mainOnly()->with(
				'children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children'
			)->get();
			
			
			return view('charts.index',compact('charts'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(Request $request)
		{
			
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function show(Chart $chart)
		{
			$view = [];
			
			
			if ($chart->slug == 'gateway')
				$view = $this->chart_gateway_view($chart);
			
			
			
			if ($chart->slug == 'stock')
				$view = $this->chart_stock_view($chart);
			
			
			
			
			return $view;
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function edit(Chart $chart)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function update(Request $request,Chart $chart)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Chart $chart
		 *
		 * @return Response
		 */
		public function destroy(Chart $chart)
		{
			//
		}
	}
