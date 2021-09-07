<?php

	namespace App\Http\Controllers\App\Web;

	use App\Http\Controllers\Controller;
	use App\Models\Item;
	use App\Models\WarrantySubscription;
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
