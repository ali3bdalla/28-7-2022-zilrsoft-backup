<?php
	
	namespace App\Http\Controllers;
	
	use App\Invoice;
	use App\InvoiceItems;
	use App\ItemExpenses;
	use App\SaleInvoice;
	use Mpdf\Mpdf;
	
	
	class HomeController extends Controller
	{
		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
		
		
		
//			$this->middleware('auth');
		}
		
		/**
		 * Show the application dashboard.
		 *
		 * @return \Illuminate\Contracts\Support\Renderable
		 */
		public function index()
		{
			
//			return  auth()->user()->organization->initData(auth()->user());
			//return  SaleInvoice::all();
			
			return view('home');
		}
		
		public function receipt()
		{
			
			$invoice = Invoice::latest()->first();

			return view('template.receipt.pos',compact('invoice'));
		}
		
		public function a4()
		{

			
			$invoice = Invoice::inRandomOrder()->first();

			return view('template.test');
		}
		
	}
