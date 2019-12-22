<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	use Mpdf\Mpdf;
	
	
	class HomeController extends Controller
	{
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			
			return view('accounting.dashboard.index');
		}
		
	}
