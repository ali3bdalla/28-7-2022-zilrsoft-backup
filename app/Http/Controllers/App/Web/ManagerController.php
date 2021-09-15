<?php

namespace App\Http\Controllers\App\Web;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Accounting\Manager\CreateManagerRequest;
    use App\Http\Requests\Accounting\Manager\DatatableRequest;
    use App\Http\Requests\Accounting\Manager\UpdateManagerRequest;
    use App\Models\Account;
    use App\Models\Branch;
    use App\Models\DeliveryMan;
    use App\Models\Manager;
    use Exception;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    use Illuminate\Http\Response;

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

            return view('accounting.managers.index', compact('branches'));
        }

        /**
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
            $gateways = Account::where('slug', 'gateway')->get();
            $deliveryMen = DeliveryMan::all();

            return view('accounting.managers.create', compact('branches', 'gateways', 'deliveryMen'));
        }

        /**
         * @throws Exception
         *
         * @return |null |null |null
         */
        public function store(CreateManagerRequest $request)
        {
            return $request->save();
        }

        /**
         * Display the specified resource.
         */
        public function show(Manager $manager)
        {
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit(Manager $manager)
        {
            // return 1;

            $dbGateways = $manager->gateways; //()->pluck('id')->toArray()
            $manager_gateways = [];
            foreach ($dbGateways as $gateway) {
                $manager_gateways[] = $gateway->id;
            }
            // return $manager_gateways;

            $deliveryMen = DeliveryMan::all();
            $manager_permissions = $manager->permissions()->pluck('name');
            $branches = Branch::with('departments')->get();

            return view('accounting.managers.edit', compact('branches', 'manager', 'manager_gateways', 'manager_permissions', 'deliveryMen'));
        }

        /**
         * @throws Exception
         *
         * @return |null
         */
        public function update(UpdateManagerRequest $request, Manager $manager)
        {
            return $request->save($manager);
        }

        /**
         * @throws Exception
         */
        public function destroy(Manager $manager)
        {
            $manager->delete();
        }
    }
