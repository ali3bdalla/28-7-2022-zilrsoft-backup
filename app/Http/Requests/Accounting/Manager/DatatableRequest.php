<?php
	
	namespace App\Http\Requests\Accounting\Manager;
	
	use App\Models\Manager;
	use Carbon\Carbon;
	use Illuminate\Foundation\Http\FormRequest;
	
	class DatatableRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage managers');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				//
			];
		}
		
		public function data()
		{
			
			$query = Manager::with('user.creator','department','branch');
			
			
			if ($this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
				$this->filled('endDate')){
				$_startDate = Carbon::parse($this->input("startDate"))->toDateString();
				$_endDate = Carbon::parse($this->input("endDate"))->toDateString();
				
				
				if ($_endDate === $_startDate){
					$query = $query->whereDate('created_at',$_startDate);
				}else{
					$query = $query->whereBetween('created_at',[
						$_startDate->toDateString(),
						$_endDate->toDateString()
					]);
				}
				
				
			}

//
			if ($this->has('name') && $this->filled('name')){
				$query = $query->where('name','ILIKE','%'.$this->name.'%')->orWhere('name_ar','ILIKE','%'.$this->name.'%');
			}
			
			if ($this->has('email') && $this->filled('email')){
				$query = $query->where('email_address','ILIKE','%'.$this->email.'%');
			}
			
			
			if ($this->has('branchId') && $this->filled('branchId') && $this->input('branchId') >= 1){
				$query = $query->where('branch_id',$this->input('branchId'));
				
				if ($this->has('departmentId') && $this->filled('departmentId') && $this->input('departmentId') >= 1){
					$query = $query->where('department_id',$this->input('departmentId'));
				}
				
			}
//
			
			if ($this->has('id') && $this->filled('id')){
				$query = $query->where('id',$this->id);
			}
			
			
			if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')){
				$query = $query->orderBy($this->orderBy,$this->orderType);
			}else{
				$query = $query->orderByDesc("id");
			}
//
			
			if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")
				) >= 1 && intval($this->input('itemsPerPage')) <= 100){
				return $query->paginate(intval($this->input('itemsPerPage')));
			}else{
				return $query->paginate(20);
				
			}
			
			
		}
	}
