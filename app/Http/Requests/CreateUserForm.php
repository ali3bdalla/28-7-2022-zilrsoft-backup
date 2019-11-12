<?php
	
	namespace App\Http\Requests;
	
	use App\Events\UserCreatedEvent;
	use App\Manager;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\Rule;
	
	class CreateUserForm extends FormRequest
	{
		
		public $userData = [];
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
			return $this->user()->isAuthorizedTo('create-user');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				// //
				'name' => 'required|string',
				'phone_number' => 'required|numeric',
				'user_type' => 'required',Rule::in(['individual','company']),
				'user_title' => 'required',Rule::in(['mis','mr','company']),
				'is_manager' => 'required|boolean',
				'is_supplier' => 'required|boolean',
				'is_client' => 'required|boolean',
				'is_vendor' => 'required|boolean',
				'can_make_credit' => 'required|boolean',
				
				
				'user_detail_vat'=>'nullable',
				'user_detail_email'=>'nullable',
				'user_detail_cr'=>'nullable',
				'user_detail_address'=>'nullable',
				'user_detail_responser'=>'nullable',
				'user_detail_responser_phone'=>'nullable'
        
	    
	    
        ];
    }
		
		public function save()
		{
			
			$user = null;
			DB::beginTransaction();
			try{
				$current = $this->user();
				$data = $this->only(
					'is_manager',
					'is_supplier',
					'is_vendor',
					'is_client',
					'user_type',
					'phone_number',
					'name',
					'user_title',
					'can_make_credit'
				);
				if ($this->is_manager){
					$data['is_supervisor'] = true;
				}
				$data['creator_id'] = $current->id;
				$user = $current->organization->users()->create($data);
				if ($this->is_manager){
					$manager_data = $this->only(
						'email',
						'pin_code',
						'branch_id',
						'department_id');
					$manager_data['password'] = bcrypt($this->password);
					$manager_data['user_id'] = $current->id;
					$manager_data['organization_id'] = $current->organization_id;
					$manager_data['name'] = $this->name;
					$user['manager'] = Manager::create($manager_data);
				}
				$this->createUserDetails($user);
				event(new  UserCreatedEvent($user));
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
			return $user;
			
		}
		
		public function createUserDetails($user)
		{
			
			
			$data['email_address'] = $this->user_detail_email;
			$data['address'] = $this->user_detail_address;
			$data['cr'] = $this->user_detail_cr;
			$data['vat'] = $this->user_detail_vat;
			$data['responsible_name'] = $this->user_detail_responser;
			$data['responsible_phone_number'] = $this->user_detail_responser_phone;
			$user->details()->create($data);
			
//
//
//			'user_detail_vat'=>'nullable',
//				'user_detail_email'=>'nullable',
//				'user_email_cr'=>'nullable',
//				'user_detail_address'=>'nullable',
//				'user_detail_responser'=>'nullable',
//				'user_detail_responser_phone'=>'nullable'
		
		
		
		}
		
		public function validateManger()
		{
			$this->validate([
				'email' => 'required|email|unique:managers,email',
				'password' => 'required|confirmed|min:7',
				'pin_code' => 'required|numeric',
				'branch_id' => 'required|integer|exists:branches,id',
				'department_id' => 'required|integer|exists:departments,id',
			
			]);
			
		}
		
	}
