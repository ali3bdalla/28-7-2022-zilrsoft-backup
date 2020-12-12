<?php
	
	namespace App\Http\Requests\Accounting\Branch;
	
	use Carbon\Carbon;
	use Illuminate\Foundation\Http\FormRequest;
	
	class DepartmentsDatatableRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage branches');
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
		
		public function data($branch)
		{
			
			$query = $branch->departments();
			
			
			if ($this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
				$this->filled
				('endDate')){
				
				$_startDate = Carbon::parse($this->startDate);
				$_endDate = Carbon::parse($this->endDate);
				
				$query = $query->whereBetween('created_at',[
					$_startDate->toDateString(),
					$_endDate->toDateString()
				]);
			}
			
			
			if ($this->has('name') && $this->filled('name')){
				$query = $query->where('title','ILIKE','%'.$this->name.'%')->orWhere('ar_title','ILIKE','%'
					.$this->name
					.'%');
			}
			
			
			if ($this->has('id') && $this->filled('id')){
				$query = $query->where('id',$this->id);
			}
			
			
			if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')){
				$query = $query->orderBy($this->orderBy,$this->orderType);
			}else{
				$query = $query->orderByDesc("id");
			}
			
			
			if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")
				) >= 1 && intval($this->input('itemsPerPage')) <= 100){
				return $query->paginate(intval($this->input('itemsPerPage')));
			}else{
				return $query->paginate(20);
				
			}
			
			
		}
	}
