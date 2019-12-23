<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Branch;
	use App\Department;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Branch\CreateBranchRequest;
	use App\Http\Requests\Accounting\Branch\DatatableRequest;
	use App\Http\Requests\Accounting\Branch\DepartmentsDatatableRequest;
	use App\Http\Requests\Accounting\Branch\UpdateBranchRequest;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Routing\Redirector;
	use Illuminate\View\View;
	use Symfony\Component\HttpFoundation\Request;
	
	class BranchController extends Controller
	{
		
		public function __construct()
		{
			
			$this->middleware(['permission: manage branches']);
		}
		
		public function index()
		{
			
			return view('accounting.branches.index');
			//
		}
		
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
			//
		}
		
		public function create()
		{
			return view('accounting.branches.create');
			//
		}
		
		public function store(CreateBranchRequest $request)
		{
			
			$branch = new Branch();
			$branch->name = $request->name;
			$branch->ar_name = $request->ar_name;
			$branch->phone_number = $request->phone_number;
			$branch->organization_id = $request->user()->organization_id;
			$branch->creator_id = $request->user()->id;
			$branch->save();
			return redirect(route('accounting.branches.index'));
			//
		}
		
		/**
		 * @param Branch $branch
		 */
		public function show(Branch $branch)
		{
			//
		}
		
		/**
		 * @param Branch $branch
		 *
		 * @return Factory|View
		 */
		public function edit(Branch $branch)
		{
			return view('accounting.branches.edit',compact('branch'));
			//
		}
		
		/**
		 * @param UpdateBranchRequest $request
		 * @param Branch $branch
		 *
		 * @return RedirectResponse|Redirector
		 */
		public function update(UpdateBranchRequest $request,Branch $branch)
		{
			$branch->update($request->only('name','ar_name','phone_number'));
			return redirect(route('accounting.branches.index'));
			//
		}
		
		/**
		 * @param Branch $branch
		 */
		public function destroy(Branch $branch)
		{
			//
			
			$branch->delete();
		}
		
		/**
		 * @param Branch $branch
		 *
		 * @return Factory|View
		 */
		public function departments(Branch $branch)
		{
			
			return view('accounting.branches.departments.index',compact('branch'));
			//
		}
		
		/**
		 * @param DatatableRequest $request
		 * @param Branch $branch
		 *
		 * @return mixed
		 */
		public function departments_datatable(DepartmentsDatatableRequest $request,Branch $branch)
		{
			return $request->data($branch);
			//
		}
		
		/**
		 * @param Branch $branch
		 *
		 * @return Factory|View
		 */
		public function create_department(Branch $branch)
		{
			return view('accounting.branches.departments.create',compact('branch'));
			//
		}
		
		/**
		 * @param CreateBranchRequest $request
		 * @param Branch $branch
		 *
		 * @return RedirectResponse|Redirector
		 */
		public function store_department(Request $request,Branch $branch)
		{
			$request->validate([
				'name' => 'required|string|min:3',
				'ar_name' => 'required|string|min:3',
			]);
			$department = new Department();
			$department->title = $request->name;
			$department->ar_title = $request->ar_name;
			$department->organization_id = $request->user()->organization_id;
			$department->creator_id = $request->user()->id;
			$department->branch_id = $branch->id;
			$department->save();
			return redirect(route('accounting.branches.departments.index',$branch->id));
			//
		}
		
		/**
		 * @param Branch $branch
		 * @param Department $department
		 *
		 * @return Factory|View
		 */
		public function edit_department(Branch $branch,Department $department)
		{
			return view('accounting.branches.departments.edit',compact('branch','department'));
			//
		}
		
		/**
		 * @param UpdateBranchRequest $request
		 * @param Branch $branch
		 * @param Department $department
		 *
		 * @return RedirectResponse|Redirector
		 */
		public function update_department(Request $request,Branch $branch,Department $department)
		{
			$request->validate([
				'name' => 'required|string|min:3',
				'ar_name' => 'required|string|min:3',
			]);
			
			$data['title'] = $request->input('name');
			$data['ar_title'] = $request->input('ar_name');
			$department->update($data);
			return redirect(route('accounting.branches.departments.index',$branch->id));
			//
		}
		
		/**
		 * @param Branch $branch
		 * @param Department $department
		 */
		public function destroy_department(Branch $branch,Department $department)
		{
			$department->delete();
			//
		}
	}
