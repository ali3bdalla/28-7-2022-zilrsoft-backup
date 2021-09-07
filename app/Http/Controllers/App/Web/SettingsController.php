<?php
	
	namespace App\Http\Controllers\App\Web;
	
	use App\Http\Controllers\Controller;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	class SettingsController extends Controller
	{
		
		/**
		 * @return Factory|View
		 */
		public function printers()
		{
			return view('accounting.settings.printers');
		}
	}
