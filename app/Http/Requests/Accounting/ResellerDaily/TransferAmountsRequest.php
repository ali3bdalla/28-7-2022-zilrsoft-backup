<?php
	
	namespace App\Http\Requests\Accounting\ResellerDaily;
	
	use App\Models\Account;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TransferAmountsRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'amount' => 'required|price',
				'gateway_id' => 'required|integer|exists:accounts,id',
				'receiver_id' => 'required|integer|exists:managers,id',
				'receiver_gateway_id' => 'required|integer|exists:accounts,id',
			];
		}
		
		public function save()
		{
			DB::beginTransaction();
			try {
				$manager = auth()->user();
				$temp_reseller_account = Account::where([['is_system_account',true],['slug','temp_reseller_account'],])->first();
				$sender_gateway = Account::find($this->input('gateway_id'));
				$sender_gateway_remaining_amount = floatval($sender_gateway->current_amount - $this->input('amount'));

				// create transactions container
				$container = $manager->organization->transactions_containers()->create(
					[
						'creator_id' => $manager->id,
						'description' => 'transfer_amount',
						'amount' => $this->input('amount'),
						'is_pending' => true,
					]
				);
				

				// add manager private transactions
				$created_transaction = $manager->private_transactoins()->create([
					'organization_id' => $manager->organization_id,
					'transaction_type' => "transfer",
					'transaction_container_id' => $container->id,
					'receiver_id' => $this->input('receiver_id'),
					'amount' => $this->input('amount'),
					'is_pending' => true
				]);
			


				// 
				$data = [];
				$data['creator_id'] = $manager->id;
				$data['organization_id'] = $manager->organization_id;
				$data['debitable_id'] = $this->input('receiver_gateway_id');
				$data['debitable_type'] = Account::class;
				$data['amount'] = $this->input('amount');
				$data['description'] = "close_account";
				$data['is_pending'] = true;
				$container->transactions()->create($data);
				
				
				$data = [];
				$data['creator_id'] = $manager->id;
				$data['organization_id'] = $manager->organization_id;
				$data['creditable_id'] = $this->input('gateway_id');
				$data['creditable_type'] = Account::class;
				$data['is_pending'] = true;
				$data['amount'] = $sender_gateway->current_amount;
				$data['description'] = "close_account";
				$container->transactions()->create($data);
				
				
				
				
				if ($sender_gateway_remaining_amount > 0){
					$data = [];
					$data['creator_id'] = $manager->id;
					$data['organization_id'] = $manager->organization_id;
					$data['debitable_id'] = $temp_reseller_account->id;
					$data['debitable_type'] = Account::class;
					$data['amount'] = $sender_gateway_remaining_amount;
					$data['description'] = "close_account";
					$data['is_pending'] = true;
					$container->transactions()->create($data);
				}
				DB::commit();

				return $created_transaction;
			}catch(Exception $exception)
			{
				DB::rollBack();
				return response(['message' => $exception->getMessage()],500);
			}
			
			
		}
	}
