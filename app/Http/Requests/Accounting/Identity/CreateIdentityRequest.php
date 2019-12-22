<?php
	
	namespace App\Http\Requests\Accounting\Identity;
	
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\Rule;
	
	class CreateIdentityRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create identity');
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
				'name' => 'required|string',
				'ar_name' => 'required|string',
				'phone_number' => 'required|string',
				'user_type' => 'required',Rule::in(['individual','company']),
				'user_title' => 'required',Rule::in(['mis','mr','company']),
				'is_supplier' => 'required|boolean',
				'is_client' => 'required|boolean',
				'is_vendor' => 'required|boolean',
				'can_make_credit' => 'required|boolean',
				'user_gateways.*.id' => 'required|integer|exists:accounts,id',
				'user_detail_vat' => 'nullable',
				'user_detail_email' => 'nullable',
				'user_detail_cr' => 'nullable',
				'user_detail_address' => 'nullable',
				'user_detail_responser' => 'nullable',
				'user_detail_responser_phone' => 'nullable'
			];
		}
		
		public function save()
		{
			
			$user = null;
			DB::beginTransaction();
			try{
				$current = $this->user();
				$data = $this->only(
					'is_supplier',
					'is_vendor',
					'is_client',
					'user_type',
					'phone_number',
					'name',
					'user_title',
					'can_make_credit'
				);
				$data['name_ar'] = $this->ar_name;
				$data['is_manager'] = false;
				$data['creator_id'] = $current->id;
				$user = $current->organization->users()->create($data);
				if ($this->is_vendor || $this->is_client){
					if ($this->user_gateways != null){
						foreach ($this->user_gateways as $gateway){
							$user->gateways()->create([
								'creator_id' => auth()->user()->id,
								'organization_id' => $current->organization_id,
								'bank_id' => $gateway['id'],
								'detail' => $gateway['account_name'],
							]);
						}
					}
					
				}
				
				$this->createUserDetails($user);
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
			
		}
		
	}
