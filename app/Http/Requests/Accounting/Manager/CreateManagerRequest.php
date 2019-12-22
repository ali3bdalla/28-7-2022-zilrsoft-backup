<?php
	
	namespace App\Http\Requests\Accounting\Manager;
	
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class CreateManagerRequest extends FormRequest
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
				'email' => 'required|email:rfc,dns,filter|unique:managers,email',
				'password' => 'required|string|min:7|confirmed',
				'name' => 'required|string|min:2',
				'name_ar' => 'required|string|min:2',
				'branch_id' => 'required|integer|exists:branches,id',
				'department_id' => 'required|integer|exists:departments,id',
				'permissions' => 'array|nullable',
				'permissions.*' => 'string|exists:permissions,name',
			];
		}
		
		public function save()
		{
			
			$user = null;
			DB::beginTransaction();
			try{
				$current = $this->user();
				
				$data['is_manager'] = true;
				$data['is_vendor'] = false;
				$data['is_supplier'] = false;
				$data['is_client'] = false;
				$data['can_make_credit'] = false;
				$data['user_type'] = 'individual';
				$data['phone_number'] = $this->phone_number;
				$data['name_ar'] = $this->ar_name;
				$data['name'] = $this->name;
				$data['user_title'] = 'mr';
				$data['creator_id'] = $current->id;
				
				$user = $current->organization->users()->create($data);
				
				$manager = $user->manager()->create([
					'password' => bcrypt($this->password),
					'email' => $this->email,
					'name_ar' => $this->name_ar,
					'name' => $this->name,
					'organization_id' => $current->organization_id,
					'branch_id' => $this->branch_id,
					'department_id' => $this->department_id,
				]);
				
				
				if (!empty($this->permissions)){
					$manager->givePermissionTo($this->permissions);
				}
				
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
			return $user;
			
		}
		
	}
