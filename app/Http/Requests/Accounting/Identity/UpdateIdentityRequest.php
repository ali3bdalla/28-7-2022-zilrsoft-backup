<?php
	
	namespace App\Http\Requests\Accounting\Identity;
	
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\Rule;
	
	class UpdateIdentityRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit identity');
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
				'user_gateways.*.id' => 'required|integer|organization_exists:App\Models\Account,id',
				'user_detail_vat' => 'nullable',
				'email' => 'nullable|email',
				'user_detail_cr' => 'nullable',
				'email' => 'nullable',
				'user_detail_responser' => 'nullable',
				'user_detail_responser_phone' => 'nullable'
			];
		}
		
		public function save($identity)
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
				
				$identity->update($data);
//
				$identity->gateways()->delete();
				
				if ($this->is_vendor || $this->is_client){
					if ($this->user_gateways != null){
						foreach ($this->user_gateways as $gateway){
							$identity->gateways()->create([
								'creator_id' => auth()->user()->id,
								'organization_id' => $current->organization_id,
								'bank_id' => $gateway['id'],
								'detail' => $gateway['account_name'],
							]);
						}
					}
					
				}
				
				$this->updateUserDetails($identity);
				DB::commit();
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
			return $user;
			
		}
		
		public function updateUserDetails($user)
		{
			$data['email_address'] = $this->email;
			$data['address'] = $this->user_detail_address;
			$data['cr'] = $this->user_detail_cr;
			$data['vat'] = $this->user_detail_vat;
			$data['responsible_name'] = $this->user_detail_responser;
			$data['responsible_phone_number'] = $this->user_detail_responser_phone;
			$user->details()->update($data);
			
		}
	}
