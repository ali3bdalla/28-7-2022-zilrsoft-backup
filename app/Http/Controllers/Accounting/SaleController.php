<?php

	namespace App\Http\Controllers\Accounting;

	use App\Models\Account;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\Accounting\Sale\CreateSaleRequest;
    use App\Http\Requests\Accounting\Sale\DatatableRequest;
    use App\Http\Requests\Accounting\Sale\ReturnSaleRequest;
    use App\Models\Invoice;
    use App\Models\Item;
    use App\Models\Manager;
    use App\Models\User;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Http\Response;
    use Illuminate\View\View;


    class SaleController extends Controller
	{

		/**
		 * SaleController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create sale|edit sale|view sale|delete sale']);
		}

		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$clients = User::where('is_client',true)->get();
			$creators = Manager::all();
			return view('sales.index',compact('clients','creators'));

		}

		/**
		 * @param DatatableRequest $request
		 *
		 * @return mixed
		 */
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}

		/**
		 * @return Factory|View
		 */
		public function create()
		{

			$salesmen = Manager::all();
			$clients = User::where('is_client',true)->get()->toArray();
			$expenses = Item::where('is_expense',true)->get();
//			$gateways = auth()->user()->gateways()->get();
			$gateways = Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get();
			return view('accounting.sales.create',compact('clients','salesmen','gateways','expenses'));
		}

		/**
		 * @param CreateSaleRequest $request
		 *
		 * @return ResponseFactory|Response
		 */
		public function store(CreateSaleRequest $request)
		{


			return $request->save();

		}

		/**
		 * @param Invoice $sale
		 *
		 * @return Factory|View
		 */
		public function show(Invoice $sale)
		{

			// return $sale->transactions;
			$transactions = $sale->transactions()->where('description','!=','client_balance')->get();
			$invoice = $sale;

			// return $transactions;
			return view('accounting.sales.show',compact('invoice','transactions'));
			//
		}

		/**
		 * @param Invoice $sale
		 *
		 * @return Factory|View
		 */
		public function edit(
			Invoice $sale)
		{
			$invoice = $sale;
			$sale = $invoice->sale;
			$items = [];
			$data_source_items = $invoice->items()->with('item')->get();
			foreach ($data_source_items as $item){
				if ($item->item->is_need_serial){
					$item['serials'] = $item->item->serials()->sale($invoice->id)->get();
				}
				$items [] = $item;
			}

			$expenses = Item::where('is_expense',true)->get();
//			$gateways = auth()->user()->gateways()->get();
			$gateways = Account::where([['slug','temp_reseller_account'],['is_system_account',true]])->get();

			return view('accounting.sales.edit',compact('sale','invoice','items','gateways','expenses'));
		}

		/**
		 * @param Invoice $sale
		 * @param ReturnSaleRequest $request
		 *
		 * @return ResponseFactory|Response|mixed
		 */
		public function update(Invoice $sale,ReturnSaleRequest $request)
		{
			return $request->makeReturn($sale);
		}

	}
