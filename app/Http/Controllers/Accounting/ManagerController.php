<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Models\Account;
	use App\Models\Branch;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Manager\CreateManagerRequest;
	use App\Http\Requests\Accounting\Manager\DatatableRequest;
	use App\Http\Requests\Accounting\Manager\UpdateManagerRequest;
	use App\Models\Manager;
	use Exception;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Spatie\Permission\Models\Permission;
	
	class ManagerController extends Controller
	{
		public function __construct()
		{
			$this->middleware(['permission:manage managers']);
		}
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{

			$branches = Branch::with('departments')->get();
			return view('accounting.managers.index',compact('branches'));
			//
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return LengthAwarePaginator
		 */
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$branches = Branch::with('departments')->get();
			$gateways = Account::where('slug','gateway')->get();
			return view('accounting.managers.create',compact('branches','gateways'));
			//
		}
		
		/**
		 * @param CreateManagerRequest $request
		 *
		 * @return |null |null |null
		 * @throws Exception
		 */
		public function store(CreateManagerRequest $request)
		{
			return $request->save();
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Manager $manager
		 *
		 * @return void
		 */
		public function show(Manager $manager)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Manager $manager
		 *
		 * @return void
		 */
		public function edit(Manager $manager)
		{
			// return 1;

			$dbGateways = $manager->gateways;//()->pluck('id')->toArray()
			$manager_gateways = [];
			foreach($dbGateways as $gateway)
			{
				$manager_gateways[] = $gateway->id;
			}
			// return $manager_gateways;
			
			$manager_permissions = $manager->permissions()->pluck('name');
			$branches = Branch::with('departments')->get();
			return view('accounting.managers.edit',compact('branches','manager','manager_gateways','manager_permissions'));
			//
		}
		
		/**
		 * @param UpdateManagerRequest $request
		 * @param Manager $manager
		 *
		 * @return |null
		 * @throws Exception
		 */
		public function update(UpdateManagerRequest $request,Manager $manager)
		{
			
			
			return $request->save($manager);
			//
		}
		
		/**
		 * @param Manager $manager
		 *
		 * @throws Exception
		 */
		public function destroy(Manager $manager)
		{
			$manager->delete();
			//
		}
	}
