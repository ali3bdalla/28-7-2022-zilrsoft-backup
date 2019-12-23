<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Http\Controllers\Controller;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	
	class HomeController extends Controller
	{
		public function __construct(){
			$this->middleware('auth');
		}
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			return view('accounting.dashboard.index');
		}
		
	}
