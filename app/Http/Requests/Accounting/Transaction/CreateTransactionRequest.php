<?php
	
	namespace App\Http\Requests\Accounting\Transaction;
	
	use App\Account;
	use App\Accounting\IdentityAccounting;
	use App\User;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\ValidationException;
	
	class CreateTransactionRequest extends FormRequest
	{
		
		use  IdentityAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create transaction');
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
				'transactions' => 'required|array',
				"transactions.*.id" => "required|integer|exists:accounts,id",
				"transactions.*.credit_amount" => "required|numeric",
				"transactions.*.debit_amount" => "required|numeric",
				"transactions.*.is_credit" => "required|boolean",
				"transactions.*.vendor_id" => "nullable|integer|exists:users,id",
				"transactions.*.client_id" => "nullable|integer|exists:users,id",
				"transactions.*.item_id" => "nullable|integer|exists:items,id",
				'description' => "required|string",
				'amount' => "required",
			];
		}
		
		public function save()
		{
			DB::beginTransaction();
			
			
			try{
				
				$this->validateAmounts();
				$container = auth()->user()->organization->transactions_containers()->create(
					[
						'creator_id' => auth()->user()->id,
						'description' => $this->description,
						'amount' => $this->amount,
					]
				);
				
				
				foreach ($this->transactions as $transaction){
					
					if ($transaction['slug'] == 'stock'){
						$this->toCreateStockTransaction($transaction,$container);
					}elseif ($transaction['slug'] == 'clients'){
						$this->toCreateClientTransaction($transaction,$container);
					}elseif ($transaction['slug'] == 'vendors'){
						$this->toCreateVendorTransaction($transaction,$container);
					}else{
						$data = [];
						$data['creator_id'] = auth()->user()->id;
						$data['organization_id'] = auth()->user()->organization_id;
						if ($transaction['is_credit']){
							$data['creditable_id'] = $transaction['id'];
							$data['creditable_type'] = Account::class;
							$data['amount'] = $transaction['credit_amount'];
						}else{
							
							$data['debitable_id'] = $transaction['id'];
							$data['debitable_type'] = Account::class;
							$data['amount'] = $transaction['debit_amount'];
						}
						
						$container->transactions()->create($data);
					}
					
					
				}
				
				DB::commit();
			}catch (Exception $exception){
				DB::rollBack();
				return response($exception->getTrace())->status(400);
			}
			
			
		}
		
		public function validateAmounts()
		{
			$total_credit = 0;
			$total_debit = 0;
			foreach ($this->transactions as $transaction){
//				dd($transaction);
				if ($transaction['is_credit'])
					$total_credit = $total_credit + $transaction['credit_amount'];
				else
					$total_debit = $total_debit + $transaction['debit_amount'];
			}
			
			if ($total_debit !== $total_credit){
				
				$error = ValidationException::withMessages([
					'debit_and_credit' => ['debit does\'t equal credit amount'],
				]);
				throw $error;
				
			}
		}
		
		public function toCreateStockTransaction($transaction,$container)
		{
		
		}
		
		public function toCreateClientTransaction($transaction,$container)
		{
			$client = User::findOrFail($transaction['client_id']);
			
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			if ($transaction['is_credit']){
				$data['creditable_id'] = $transaction['id'];
				$data['creditable_type'] = Account::class;
				$data['amount'] = $transaction['credit_amount'];
				$this->toUpdateClientBalance($client,'sub',$transaction['credit_amount']);
				
			}else{
				
				$data['debitable_id'] = $transaction['id'];
				$data['debitable_type'] = Account::class;
				$data['amount'] = $transaction['debit_amount'];
				$this->toUpdateClientBalance($client,'plus',$transaction['debit_amount']);
			}
			$data['user_id'] = $transaction['client_id'];
			$data['description'] = "client_balance";
			$container->transactions()->create($data);
			
			
		}
		
		public function toCreateVendorTransaction($transaction,$container)
		{
			
			$vendor = User::findOrFail($transaction['vendor_id']);
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			if ($transaction['is_credit']){
				$data['creditable_id'] = $transaction['id'];
				$data['creditable_type'] = Account::class;
				$data['amount'] = $transaction['credit_amount'];
				
				$this->toUpdateVendorBalance($vendor,'plus',$transaction['credit_amount']);
			}else{
				
				$data['debitable_id'] = $transaction['id'];
				$data['debitable_type'] = Account::class;
				$data['amount'] = $transaction['debit_amount'];
				$this->toUpdateVendorBalance($vendor,'sub',$transaction['debit_amount']);
			}
			
			$data['user_id'] = $transaction['vendor_id'];
			$data['description'] = "vendor_balance";
			$container->transactions()->create($data);
			
			
		}
		
	}
