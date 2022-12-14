<?php

	namespace App\Http\Requests\Accounting\Manager;

	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;

	class UpdateManagerRequest extends FormRequest
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
				'id' => 'required|integer|exists:managers,id',
				'email' => 'required|email:rfc,dns,filter|unique:managers,email,'.request('id'),
				'password' => 'nullable|string|min:7|confirmed',
				'name' => 'required|string|min:2',
				'name_ar' => 'required|string|min:2',
				'phone_number' => 'required|string',
				'branch_id' => 'required|integer|exists:branches,id',
				'department_id' => 'required|integer|exists:departments,id',
				'delivery_man_id' => 'nullable|integer|exists:delivery_men,id',
				'permissions' => 'array|nullable',
				'permissions.*' => 'string|exists:permissions,name',
				'gateways' => 'array|nullable',
				'gateways.*.id' => 'integer|exists:accounts,id',
			];
		}

		public function save($manager)
		{

			$user = null;
			DB::beginTransaction();
			try{
				$data = $this->only('name','name_ar','email','department_id','branch_id','delivery_man_id',"phone_number");
				$data['password'] = $this->password == "" ? $manager->password : bcrypt($this->password);
				$manager->update($data);

				$manager->user()->update([
					'phone_number' => $this->phone_number,
					'name_ar' => $this->name_ar,
					'name' => $this->name
				]);
				$manager->gateways()->detach();

				if (!empty($this->gateways)){

					if (!empty($this->gateways)){
						foreach ($this->gateways as $gateway){
							$manager->gateways()->attach(
								$gateway['id']
								,[
								'organization_id' => auth()->user()->organization_id
							]);
						}

					}

				}
				$manager->permissions()->detach();
				if (!empty($this->permissions)){
					$manager->givePermissionTo($this->permissions);
				}

				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}

//
			return $user;

		}
	}
